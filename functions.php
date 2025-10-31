<?php

require_once(__DIR__ . '/database/DBController.php');
require_once(__DIR__ . '/database/Product.php');
require_once(__DIR__ . '/database/Cart.php');


// DBController object
$db = new DBController();

// Product object
$product = new Product($db);
$product_shuffle = $product->getData();

// Cart object
$Cart = new Cart($db );
