<?php

namespace App\Http\Controllers\Scraper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Scraper\RequestController;
use App\Http\Controllers\Scraper\CustomersController;
use Symfony\Component\DomCrawler\Crawler;
use App\Customers;
use App\Orders;
use Webpatser\Uuid\Uuid;

class OrderController extends Controller
{	
	private static $_order_id;
	private $_order_dom;
	
	public function info($temp_id){
		$params = [
			'form_params' => [
				'id' => $temp_id
			]
		];	

		$this->_order_dom = RequestController::crawler('https://orders.takeaway.com/orders/details', 'post', $params);

		$order_info = [
			'customer' => $this->customer(),
			'details' => $this->details(),
			'products' => $this->products()
		];

		Self::DB($order_info);

		return $order_info;	
	}


	private function orderDom($element){
		return $this->_order_dom->filterXPath($element);
	}

	private function deliverCosts(){
		$count = $this->orderDom('//table[@class="products"]/tbody/tr')->count();
		return $count - 2;
	}

	private function details(){
		$order_id = $this->orderDom('//div[@class="order-status first done"]')
							->html();
		
		$_order_id = trim(explode('<span', $order_id)[0]);							

		$order_placed	= trim($this->orderDom('//div[@class="order-status first done"]/span')
							->text());

		$payment_method = $this->orderDom('//div[@class="summary"]/div/p')
							->eq(1)
							->text();

		$delivery_costs = $this->orderDom('//table[@class="products"]/tbody/tr')
							->eq($this->deliverCosts())
							->filterXPath('//td')
							->last()
							->text();

		$total_price 	= $this->orderDom('//table[@class="products"]/tbody/tr')
							->last()
							->filterXPath('//td')
							->last()
							->text();

		$order_info = [
			'order_placed' => $order_placed,
			'payment_method' => $payment_method,
			'delivery_costs' => str_replace(',', '.', $delivery_costs),
			'total_price' => str_replace(',', '.', $total_price),
			'order_id' => $_order_id
		];													

		return $order_info;
	}

	private function products(){
		$products_dom = $this->orderDom('//table[@class="products"]/tbody')->first()->html();
		$products = new ProductsController($products_dom);	
		$products = $products->info($products_dom);

		return $products;
	}

	private function customer(){
		$customer_dom = $this->orderDom('//div[@class="summary"]/div[@class="content"]/p')->first()->html();

		$customer = new CustomersController();	
		$customer = $customer->info($customer_dom);

		return $customer;
	}	

	private static function DB($order_info){
		$customer = Customers::where('address', $order_info['customer']['address'])->get()->first();

		if (!$customer) {
			$customer_id = Uuid::generate();

			CustomersController::saveDB($customer_id, $order_info['customer']);	
		} else {
			$customer_id = $customer->customer_id; 
		}

		ProductsController::saveDB($order_info['products'], $order_info['details']['order_id'], $customer_id);

		return self::save($order_info, $customer_id);
	}

	private static function save($order_info, $customer_id){
		$details = $order_info['details'];

		$order = Orders::firstOrNew(['order_id' => $details['order_id']]);

			$order->delivery_costs = $details['delivery_costs'];
			$order->customer_id = $customer_id;
			$order->total_price = $details['total_price'];
			$order->payment_method = $details['payment_method'];
			$order->order_placed = $details['order_placed'];
			$order->delivery_costs = $details['delivery_costs'];
			$order->save();
	}
}
