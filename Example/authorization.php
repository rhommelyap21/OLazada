<?php 

// import autoload and the package
require '../vendor/autoload.php';
use ofi\olazada\OLazada;

// ask for system to turn on debuging
OLazada::DEBUG_ON();

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
OLazada::authorization($callback);