<?php
$brand = array_map(function ($pro){ return $pro['item_brand']; }, $product_shuffle);
$unique = array_unique($brand);
sort($unique);
shuffle($product_shuffle);

// Handle Add to Cart
if($_SERVER['REQUEST_METHOD'] == "POST"){
    if (isset($_POST['special_price_submit'])){
        $Cart->addToCart($_POST['user_id'], $_POST['item_id']);
        header("Location: " . $_SERVER['PHP_SELF']); // reload page
        exit;
    }
}

// Items already in cart
$in_cart = $Cart->getCartId($product->getData('cart'));
?>

<section id="special-price">
    <div class="container">
        <h4 class="font-rubik font-size-20">Special Price</h4>

        <!-- Brand Filter Buttons -->
        <div id="filters" class="button-group text-right font-baloo font-size-16 mb-3">
            <button class="btn is-checked" data-filter="*">All Brands</button>
            <?php
                foreach($unique as $brand){
                    printf('<button class="btn" data-filter=".%s">%s</button>', $brand, $brand);
                }
            ?>
        </div>

        <div class="grid">
            <?php foreach($product_shuffle as $item): ?>
                <div class="grid-item border <?php echo $item['item_brand'] ?? "Brand"; ?>">
                    <div class="item py-3 px-2" style="width: 230px;">
                        <div class="product font-rale text-center">
                            <a href="<?php printf('%s?item_id=%s', 'product.php',  $item['item_id']); ?>">
                                <div class="img-container">
                                    <img src="<?php echo $item['item_image'] ?? "./assets/products/13.png"; ?>" 
                                         alt="<?php echo $item['item_name']; ?>" 
                                         class="product-image">
                                </div>
                            </a>
                            <h6 class="mt-2"><?php echo $item['item_name'] ?? "Unknown"; ?></h6>
                            <div class="rating text-warning font-size-12">
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="far fa-star"></i></span>
                            </div>
                            <div class="price py-2">
                                <span>$<?php echo $item['item_price'] ?? 0 ?></span>
                            </div>

                            <form method="post">
                                <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                                <input type="hidden" name="user_id" value="1">
                                <?php
                                if (in_array($item['item_id'], $in_cart)){
                                    echo '<button type="submit" disabled class="btn btn-success font-size-12">In Cart</button>';
                                } else {
                                    echo '<button type="submit" name="special_price_submit" class="btn btn-warning font-size-12">Add to Cart</button>';
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

<!-- CSS fix -->
<style>
#special-price .img-container {
    width: 100%;
    height: 220px; /* uniform height */
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-radius: 10px;
    background: #f8f9fa; /* light background for contrast */
}

#special-price .product-image {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain; /* keeps image proportion */
    transition: transform 0.3s ease;
}

#special-price .product-image:hover {
    transform: scale(1.05);
}

#special-price .grid-item {
    margin: 10px;
    border-radius: 10px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    background: white;
    transition: transform 0.2s;
}

#special-price .grid-item:hover {
    transform: translateY(-5px);
}

#special-price button.btn {
    border-radius: 20px;
    padding: 6px 15px;
}
</style>
