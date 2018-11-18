<?php
ini_set('xdebug.var_display_max_depth', 999);
ini_set('xdebug.var_display_max_children', 99999);
ini_set('xdebug.var_display_max_data', 99999);


Route::get('/', function () {
    return view('welcome');
});


Route::get('/scraper/orders', 'Scraper\OrdersController@orders');
