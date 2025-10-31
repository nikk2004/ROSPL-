<?php
include('../database/DBController.php');
include('../database/Cart.php');

$db = new DBController();
$cart = new Cart($db);

if(isset($_GET['item_id'])){
    $item_id = intval($_GET['item_id']);
    $user_id = 1; // later replace with $_SESSION['user_id']
    $cart->addToWishlist($item_id, $user_id);

    header("Location: wishlist.php");
    exit;
}
?>
