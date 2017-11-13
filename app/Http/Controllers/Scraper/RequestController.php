<?php

namespace App\Http\Controllers\Scraper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class RequestController extends Controller
{	
	public static $_instance = null;
	private $_client;

	public function __construct(){
		$headers = [
    		'user-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36',
    		'Host' => 'orders.takeaway.com',
    		'Origin' => 'https://orders.takeaway.com',
    		'Referer' => 'https://orders.takeaway.com',
	    ];

	    $form_params = [
			'form_params' => [
				'username' => env('TAKEAWAY_USERNAME'),
				'password' => env('TAKEAWAY_PASSWORD'),
				'login' => 'true',
				'language' => 'en'
			]
		];	

	    $this->_client = new Client([
    		'headers' => $headers,
    		'cookies' => new \GuzzleHttp\Cookie\CookieJar
    	]);
	    
    	$this->_client->post('https://orders.takeaway.com/', $form_params);
    	
    	return $this->_client;
	}	

	public static function getInstance(){
		if(!isset(Self::$_instance)){
			Self::$_instance = new RequestController();			
		}

		return Self::$_instance;
	}

	public function client(){
		return $this->_client;
	}

	public static function crawler($url, $request, $params = null){
		$client = Self::getInstance()->client();

		$response = (!$params) ? $client->$request($url) : $client->$request($url, $params);

		$crawler = new Crawler();
		$crawler->addHtmlContent(utf8_decode((String) $response->getBody()));

		return $crawler;
	}
}
