<?php
session_start();

// 1️⃣ Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// 2️⃣ Enable error reporting (development only)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();

// 3️⃣ Include classes only once
require_once(__DIR__ . '/database/DBController.php');
require_once(__DIR__ . '/database/Product.php');
require_once(__DIR__ . '/database/Cart.php');


// 4️⃣ Initialize objects
$db = new DBController();
$product = new Product($db);
$Cart = new Cart($db);

// 5️⃣ Get products and shuffle for Top Sale
$product_shuffle = $product->getData('products');
shuffle($product_shuffle);

// 6️⃣ Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['top_sale_submit'])) {
        $Cart->addToCart($_POST['user_id'], $_POST['item_id']);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    if (isset($_POST['special_price_submit'])) {
        $Cart->addToCart($_POST['user_id'], $_POST['item_id']);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// 7️⃣ Items already in cart
$in_cart = $Cart->getCartId($product->getData('cart'));

// 8️⃣ For brand filter in Special Price
$brand = array_map(function ($pro){ return $pro['item_brand']; }, $product_shuffle);
$unique = array_unique($brand);
sort($unique);
shuffle($product_shuffle); // Shuffle again for Special Price
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Shopee</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

    <!-- Owl-carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
// 9️⃣ Include header
include(__DIR__ . '/header.php');

// 10️⃣ Include main sections
include(__DIR__ . '/Template/_products.php');
include(__DIR__ . '/Template/_top-sale.php');
include(__DIR__ . '/Template/_special-price.php');

// 11️⃣ Include footer
include(__DIR__ . '/footer.php');
?>

<!-- JS Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>

<script>
$(document).ready(function(){
    // Owl Carousel for Top Sale
    $("#top-sale .owl-carousel").owlCarousel({
        loop:true,
        margin:10,
        nav:true,
        dots:false,
        responsive:{
            0:{ items:1 },
            600:{ items:3 },
            1000:{ items:4 }
        }
    });

    // Isotope filter for Special Price
    var $grid = $('.grid').isotope({
        itemSelector: '.grid-item',
        layoutMode: 'fitRows'
    });

    $('#filters').on('click', 'button', function(){
        var filterValue = $(this).attr('data-filter');
        $grid.isotope({ filter: filterValue });
    });
});
</script>
</body>
</html>
