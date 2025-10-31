<?php
// Handle Add to Wishlist
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_to_wishlist'])) {
        $item_id = intval($_POST['item_id']);
        $user_id = intval($_POST['user_id']);
        $Cart->addToWishlist($item_id, $user_id);
        header("Location: wishlist.php");
        exit;
    }

    if (isset($_POST['top_sale_submit'])) {
        $item_id = intval($_POST['item_id']);
        $user_id = intval($_POST['user_id']);
        $Cart->addToCart($item_id, $user_id);
        header("Location: cart.php");
        exit;
    }
}
?>

<style>
/* 🔧 Consistent Product Card Styling */
#top-sale .product {
    border: 1px solid #eee;
    border-radius: 15px;
    padding: 10px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    background-color: #fff;
}
#top-sale .product:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* 📏 Image Adjustment */
#top-sale .product img {
    width: 100%;
    height: 220px;           /* fixed height */
    object-fit: contain;     /* keeps full image visible */
    padding: 10px;
    border-radius: 10px;
    background-color: #f9f9f9;
}

/* 🎨 Button Styling */
#top-sale button {
    border-radius: 20px;
    transition: 0.3s;
}
#top-sale button:hover {
    transform: scale(1.05);
}

/* 🩶 Wishlist Button */
#top-sale .btn-outline-danger {
    border: 1px solid #dc3545;
}
</style>

<section id="top-sale">
    <div class="container py-5">
        <h4 class="font-rubik font-size-20">Top Sale</h4>
        <hr>
        <div class="owl-carousel owl-theme">
            <?php foreach ($product_shuffle as $item): ?>
            <div class="item py-2">
                <div class="product font-rale text-center">
                    <a href="product.php?item_id=<?php echo $item['item_id']; ?>">
                        <img src="<?php echo $item['item_image'] ?? "assets/products/1.png"; ?>" 
                             alt="<?php echo htmlentities($item['item_name']); ?>">
                    </a>
                    <div class="text-center">
                        <h6 class="mt-2"><?php echo htmlentities($item['item_name']); ?></h6>
                        <div class="rating text-warning font-size-12">
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="far fa-star"></i></span>
                        </div>
                        <div class="price py-2">
                            <span>$<?php echo number_format($item['item_price'] ?? 0, 2); ?></span>
                        </div>

                        <!-- Add to Cart -->
                        <form method="post">
                            <input type="hidden" name="item_id" value="<?php echo intval($item['item_id']); ?>">
                            <input type="hidden" name="user_id" value="1">
                            <button type="submit" name="top_sale_submit" class="btn btn-warning font-size-12 w-75">
                                🛒 Add to Cart
                            </button>
                        </form>

                        <!-- Add to Wishlist -->
                        <<!-- ✅ Add to Wishlist -->
<form method="post" action="wishlist.php" class="mt-2">
    <input type="hidden" name="item_id" value="<?php echo intval($item['item_id']); ?>">
    <input type="hidden" name="user_id" value="1"> <!-- Temporary, replace with dynamic user -->

    <?php
    // Check if item already in wishlist
    $wishlistIds = $Cart->getCartId($product->getData('wishlist'));
    if (in_array(intval($item['item_id']), $wishlistIds)) {
        echo '<button type="button" disabled class="btn btn-danger font-size-12">In Wishlist</button>';
    } else {
        echo '<button type="submit" name="add-to-wishlist" class="btn btn-outline-danger font-size-12">🤍 Add to Wishlist</button>';
    }
    ?>
</form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
