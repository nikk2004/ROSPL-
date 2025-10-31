<?php
// Ensure $in_cart is available from index.php or set to empty array
if (!isset($in_cart)) {
    $in_cart = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Shopee</title>

    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" crossorigin="anonymous" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Header -->
<header id="header">
    <div class="strip d-flex justify-content-between px-4 py-1 bg-light">
        <p class="font-rale font-size-12 text-black-50 m-0">
            Jordan Calderon 430-985 Eleifend St. Duluth Washington 92611 (427) 930-5255
        </p>
        <div class="font-rale font-size-14">
            <a href="/Mobile_shopee/Template/login.php" class="px-3 border-right border-left text-dark">Login</a>
            <a href="wishlist.php" class="px-3 border-right text-dark">Wishlist (0)</a>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark color-second-bg">
        <a class="navbar-brand" href="index.php">Mobile Shopee</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav m-auto font-rubik">
                <li class="nav-item active"><a class="nav-link" href="on-sale.php">On Sale</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" data-toggle="dropdown">
                        Category
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#top-sale">Top Sale</a>
                        <a class="dropdown-item" href="#special-price">Special Price</a>
                        <a class="dropdown-item" href="#new-phones">New Phones</a>
                    </div>
                </li>
                <li class="nav-item"><a class="nav-link" href="product.php">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="#blogs">Blog</a></li>
                <li class="nav-item"><a class="nav-link" href="Template\comingsoon.php">Coming Soon</a></li>
            </ul>
            <form action="cart.php" class="font-size-14 font-rale">
                <a href="cart.php" class="py-2 rounded-pill color-primary-bg d-flex align-items-center">
                    <span class="font-size-16 px-2 text-white"><i class="fas fa-shopping-cart"></i></span>
                    <span class="px-3 py-2 rounded-pill text-dark bg-light">
                        <?php echo count($in_cart); ?>
                    </span>
                </a>
            </form>
        </div>
    </nav>
</header>
<!-- !Header -->
