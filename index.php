<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();

// Include header
include('header.php');

// Include classes
require_once(__DIR__ . '/database/DBController.php');
require_once(__DIR__ . '/database/Product.php');
require_once(__DIR__ . '/database/Cart.php');

// Initialize objects
$db = new DBController();
$product = new Product($db);
$Cart = new Cart($db);

// Get all products
$product_shuffle = $product->getData('products');
shuffle($product_shuffle); // Shuffle for Top Sale

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_id = 1; // Default user
    $item_id = $_POST['item_id'] ?? 0;

    if ($item_id > 0) {
        $Cart->addToCart($item_id, $user_id);
        // Redirect to avoid form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Get items already in cart
$in_cart = $Cart->getCartId($Cart->getCart(1));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Shopee</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
// Include template sections
include(__DIR__ . '/Template/_banner-area.php');
include(__DIR__ . '/Template/_top-sale.php');
include(__DIR__ . '/Template/_special-price.php');
include(__DIR__ . '/Template/_banner-ads.php');
include(__DIR__ . '/Template/_new-phones.php');
include(__DIR__ . '/Template/_blogs.php');

// Include footer
include('footer.php');
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
