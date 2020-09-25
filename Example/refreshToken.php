<?php

// import autoload and the package
require '../vendor/autoload.php';
use ofi\olazada\OLazada;

// Ask to our system to turn on Debug
OLazada::DEBUG_ON();

$refresh = OLazada::Manual()
            // You can set manual service endpoint like this
            -> setRegion('Indonesia', "https://auth.lazada.com/rest")
            -> setAppSecret('YOUR App Secret Key Here')
            -> setAppKey('Your APP Id Here')
            -> setUrl('/auth/token/refresh')
            -> addApiParam('refresh_token', "YOUR Refresh Token Here")
            -> go();

// Print the result
echo $refresh;