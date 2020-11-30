<?php

/**
 * Olazada in OOP (Object Oriented Programming)
 * You can see like this
 */

 // import autoload and the package
require '../vendor/autoload.php';
use ofi\olazada\OLazada;

class oop {

    public function __construct()
    {   
        // Set App Key, App Secret and service endpoint
        $appKey = "YOUR_APP_KEY"; 
        $secretKey = 'YOUR_SECRET_KEY'; 
        $api_url = "https://api.lazada.co.id/rest";

        new OLazada($appKey, $secretKey, $api_url);
        
        // ask for system to turn on debuging
        OLazada::DEBUG_ON();
    }

    public function getProduct()
    {
        // define addApiParam
        $addApiParam = [
            'filter' => 'all',
            'limit' => 5
        ];

        // call request method
        return OLazada::Request("/products/get", $addApiParam);
    }

    public function getCategory()
    {
        // call request method
        return OLazada::Request("/category/tree/get");
    }

    public function authorization()
    {
        /**
         * The callback URL you provided 
         * when creating the application.
         * You can see in app console here
         * in your app
         * 
         * https://open.lazada.com/app/index.htm
         * 
         * for example you can see here
         * https://freeimage.host/i/25TrQa
         */

        // You must provide this callback
        $callback = "https://olazada.herokuapp.com/Example/callback.php";

        // Call authorization function
        return OLazada::authorization($callback);
    }

    public function callback()
    {
        // Call callback function
        $result = OLazada::callback();
            
        // or you can set like this
        // $result = OLazada::callback("YOUR APP Key Here", "App Secret Here");
            
        // and print any result from callback
        echo ($result);
    }

}