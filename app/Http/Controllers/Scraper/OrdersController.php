<?php

namespace App\Http\Controllers\Scraper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Scraper\RequestController;
use App\Http\Controllers\Scraper\OrderController;

class OrdersController extends Controller
{	
    public function orders(){
    	$crawler = RequestController::crawler('https://orders.takeaway.com/orders/orders', 'post', null);
		$temp_ids = $crawler->filterXPath('//tbody[contains(@class, "wide")]')->extract(['_text', 'rel']);
		
		$orders = [];

		foreach($temp_ids as $temp_id){
			$order = new OrderController;
			$orders[] = $order->info(substr($temp_id[1], 2));
		}

		var_dump($orders);
	}
}
