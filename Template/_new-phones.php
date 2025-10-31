<!-- New Phones -->
<?php
shuffle($product_shuffle);

// Handle Add to Cart
if($_SERVER['REQUEST_METHOD'] == "POST"){
    if (isset($_POST['new_phones_submit'])){
        $Cart->addToCart($_POST['user_id'], $_POST['item_id']);
        header("Location: " . $_SERVER['PHP_SELF']); // Refresh page
        exit;
    }
}
?>

<section id="new-phones">
    <div class="container py-5">
        <h4 class="font-rubik font-size-20">New Phones</h4>
        <hr>

        <!-- owl carousel -->
        <div class="owl-carousel owl-theme">
            <?php foreach ($product_shuffle as $item) { ?>
                <div class="item py-3 px-2">
                    <div class="product font-rale text-center shadow-sm p-3 rounded bg-light">
                        <a href="<?php printf('%s?item_id=%s', 'product.php',  $item['item_id']); ?>">
                            <div class="img-container">
                                <img src="<?php echo $item['item_image'] ?? './assets/products/1.png'; ?>" 
                                     alt="<?php echo $item['item_name'] ?? 'Unknown'; ?>" 
                                     class="product-image">
                            </div>
                        </a>

                        <h6 class="mt-2"><?php echo $item['item_name'] ?? "Unknown"; ?></h6>
                        <div class="rating text-warning font-size-12 mb-2">
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="far fa-star"></i></span>
                        </div>
                        <div class="price py-2">
                            <span>$<?php echo $item['item_price'] ?? '0'; ?></span>
                        </div>

                        <form method="post">
                            <input type="hidden" name="item_id" value="<?php echo $item['item_id'] ?? '1'; ?>">
                            <input type="hidden" name="user_id" value="1">
                            <?php
                            if (in_array($item['item_id'], $Cart->getCartId($product->getData('cart')) ?? [])){
                                echo '<button type="submit" disabled class="btn btn-success font-size-12">In the Cart</button>';
                            } else {
                                echo '<button type="submit" name="new_phones_submit" class="btn btn-warning font-size-12">Add to Cart</button>';
                            }
                            ?>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
        <!-- !owl carousel -->
    </div>
</section>

<!-- CSS for uniform image size -->
<style>
#new-phones .img-container {
    width: 100%;
    height: 220px; /* fixed height for all */
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-radius: 12px;
    background-color: #f8f9fa;
}

#new-phones .product-image {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
}

#new-phones .product-image:hover {
    transform: scale(1.05);
}

#new-phones .product {
    background: #fff;
    border-radius: 15px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

#new-phones .product:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
}

#new-phones h6 {
    font-weight: 600;
    font-size: 15px;
}

#new-phones .price span {
    font-weight: bold;
    font-size: 14px;
    color: #333;
}

#new-phones button.btn {
    border-radius: 20px;
    padding: 6px 15px;
}
</style>
