<?php
// Ensure Product class is available
require_once(__DIR__ . '/../database/Product.php');

// Get the item_id from URL
$item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;

// Fetch product details
$product_details = $product->getProduct($item_id);

if (!$product_details) {
    echo "<div class='container py-5'><h4>Product not found</h4></div>";
    return;
}

// Calculate savings
$item_mrp = isset($product_details['item_mrp']) ? floatval($product_details['item_mrp']) : 0;
$item_price = isset($product_details['item_price']) ? floatval($product_details['item_price']) : 0;
$you_save = $item_mrp - $item_price;
?>

<section id="product" class="py-3">
    <div class="container">
        <div class="row">
            <!-- Product Image -->
            <div class="col-sm-6">
                <img src="<?php echo $product_details['item_image'] ?? './assets/products/1.png'; ?>" 
                     alt="<?php echo htmlentities($product_details['item_name'] ?? 'Product'); ?>" 
                     class="img-fluid" style="max-height:400px; object-fit:contain;">
                <div class="form-row pt-4 font-size-16 font-baloo">
                    <div class="col">
                        <button type="submit" class="btn btn-danger form-control">Proceed to Buy</button>
                    </div>
                    <div class="col">
                        <form method="post">
                            <input type="hidden" name="item_id" value="<?php echo intval($product_details['item_id']); ?>">
                            <input type="hidden" name="user_id" value="1">
                            <?php
                            $in_cart_ids = $Cart->getCartId($Cart->getCart(1));
                            if (in_array(intval($product_details['item_id']), $in_cart_ids)) {
                                echo '<button type="button" disabled class="btn btn-success form-control">In the Cart</button>';
                            } else {
                                echo '<button type="submit" name="product_submit" class="btn btn-warning form-control">Add to Cart</button>';
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="col-sm-6 py-5">
                <h5 class="font-baloo font-size-20"><?php echo htmlentities($product_details['item_name']); ?></h5>
                <small>by <?php echo htmlentities($product_details['item_brand'] ?? 'Unknown Brand'); ?></small>
                <div class="d-flex my-2">
                    <div class="rating text-warning font-size-12">
                        <span><i class="fas fa-star"></i></span>
                        <span><i class="fas fa-star"></i></span>
                        <span><i class="fas fa-star"></i></span>
                        <span><i class="fas fa-star"></i></span>
                        <span><i class="far fa-star"></i></span>
                    </div>
                    <a href="#" class="px-2 font-rale font-size-14">20,534 ratings | 1000+ answered questions</a>
                </div>
                <hr>

                <!-- Product Price -->
                <table class="my-3">
                    <tr class="font-rale font-size-14">
                        <td>M.R.P:</td>
                        <td><strike>$<?php echo number_format($item_mrp, 2); ?></strike></td>
                    </tr>
                    <tr class="font-rale font-size-14">
                        <td>Deal Price:</td>
                        <td class="font-size-20 text-danger">
                            $<?php echo number_format($item_price, 2); ?>  
                            <small class="text-dark font-size-12">&nbsp;&nbsp;Inclusive of all taxes</small>
                        </td>
                    </tr>
                    <tr class="font-rale font-size-14">
                        <td>You Save:</td>
                        <td><span class="font-size-16 text-danger">$<?php echo number_format($you_save, 2); ?></span></td>
                    </tr>
                </table>

                <!-- Product Description -->
                <div>
                    <h6 class="font-rubik">Product Description</h6>
                    <hr>
                    <p class="font-rale font-size-14">
                        <?php echo htmlentities($product_details['item_description'] ?? 'No description available.'); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
