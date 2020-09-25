<?php

/**
 * For get all product from lazada server
 * you can read this API reference
 * https://open.lazada.com/doc/api.htm?spm=a2o9m.11193531.0.0.6cfc6bbezmxvKO#/api?cid=5&path=/products/get
 */

// import autoload and the package
require '../vendor/autoload.php';
use ofi\olazada\OLazada;

// ask for system to turn on debuging
OLazada::DEBUG_ON();

// define addApiParam
$addApiParam = [
    'filter' => 'all',
    'limit' => 5
];

// call request method
$request = OLazada::Request("/products/get", $addApiParam);

// print the result
echo $request;