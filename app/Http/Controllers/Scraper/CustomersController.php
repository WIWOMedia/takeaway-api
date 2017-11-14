<?php

namespace App\Http\Controllers\Scraper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customers;

class CustomersController extends Controller
{
    private $_customer_info = [];

    public function info($customer_dom){
        $customer = explode("<br>", $customer_dom);

        $this->_customer_info = [
            'name' => $customer[0],
            'address' => $customer[1],
            'city' => $customer[3],
            'zip_code' => substr($customer[2], 0, 6),
            'phone' => $customer[4],
        ];

        return $this->_customer_info;
    }

    public static function saveDB($customer_id, $order_info){
        $customer = new Customers;
        $customer->customer_id = $customer_id;

        foreach ($order_info['customer'] as $key => $value) {
            $customer->$key = $value;
        }

        $customer->save();
    }
}
