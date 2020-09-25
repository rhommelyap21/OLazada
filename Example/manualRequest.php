<?php

// import autoload and the package
require '../vendor/autoload.php';
use ofi\olazada\OLazada;

// Ask to our system to turn on Debug
OLazada::DEBUG_ON();

/**
 * In this section i want to give
 * a code sample, how to use OLazada
 * Manual request with Chain Method
 * 
 * I want to get seller product
 */

 $request = OLazada::Manual()

            // Set your region
            // Available in :
            // Singapore
            // Indonesia
            // Vietnam
            // Malaysia
            // Philippines
            // Thailand
            ->setRegion('Indonesia')

            // Now you must set you APP Key
            -> setAppKey('YOUR APP Key')

            // And you must set your App Secrey key
            -> setAppSecret('Your APP Secret')

            // Is you have a access token, from callback process
            // you can set here
            -> setAccessToken('YOUR Access Token Here')

            // Set URL to get product
            -> setUrl('/products/get')

            // Add some parameter to request with addApiParam
            -> addApiParam('offset', '0')
            -> addApiParam('limit', '5')

            // and execute now with run() method
            -> run()
            // -> run("POST")
            // Note default is GET

            // or you can execute with go()
            // -> go("POST")
            // -> go("GET")
 ;

//  And now print prety the request
echo "<pre>";
print_r($request);
echo "</pre>";
