<?php

require '../vendor/autoload.php';
use ofi\olazada\OLazada;

/**
 * This php file is to use define all 
 * environment like a secreykey app key and etc
 */

// Set App Key, App Secret and service endpoint
$appKey = "YOUR_APP_KEY"; 
$secretKey = 'YOUR_SECRET_KEY'; 
$api_url = "https://api.lazada.co.id/rest";

new OLazada($appKey, $secretKey, $api_url);