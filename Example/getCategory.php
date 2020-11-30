<?php

/**
 * API reference
 * https://open.lazada.com/doc/api.htm?spm=a2o9m.11193531.0.0.6cfc6bbezmxvKO#/api?cid=5&path=/category/tree/get
 */

// import define file and package
include __DIR__ . '/define.php';
use ofi\olazada\OLazada;

// ask for system to turn on debuging
OLazada::DEBUG_ON();

// call request method
$request = OLazada::Request("/category/tree/get");

// print the result
echo $request;