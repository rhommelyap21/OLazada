<?php 

// import autoload and the package
require 'vendor/autoload.php';
use ofi\olazada\OLazada;

// Set App Key, App Secret and service endpoint
new OLazada(
    122777, 
    'QXjwckXmTnohKaAas22X3BbDjIulTOvk', 
    "https://api.lazada.co.id/rest"
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OLazada Client</title>
</head>
<body>
    <h1>OLazada Client</h1>
    <ul>
        <li>
            To authorization you can <a href="Example/authorization.php">click here </a> and callback.php will catch all response
            from lazada server
        </li>
        <li>
            After you finish from authorization step, you can try to get all product as json <a href="Example/getProduct.php">here</a>
        </li>
        <li>
            And you can see seller product category <a href="Example/getCategory.php">here</a>
        </li>
        <li>
            If you want to see your configuration, you can <a href="Example/printDebug.php">print out here</a>
        </li>
    </ul>
</body>
</html>