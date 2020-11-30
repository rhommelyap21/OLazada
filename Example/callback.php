<?php

// import define file and package
include __DIR__ . '/define.php';
use ofi\olazada\OLazada;

// ask for system to turn on debuging
OLazada::DEBUG_ON();

// Call callback function
$result = OLazada::callback();

// or you can set like this
// $result = OLazada::callback("YOUR APP Key Here", "App Secret Here");

// and print any result from callback
echo ($result);