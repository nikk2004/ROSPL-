<?php
include('database/DBController.php');
include('database/Product.php');
include('database/Cart.php');

$db = new DBController();
$product = new Product($db);
$Cart = new Cart($db);

$user_id = 1; // Replace with dynamic logged-in user

// ---------------- Handle POST Actions ----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Add to wishlist (from product page)
    if (isset($_POST['add-to-wishlist'])) {
        $item_id = intval($_POST['item_id']);
        $Cart->addToWishlist($item_id, $user_id);
        // Do NOT redirect here if you want AJAX-like behavior
    }

    // Delete from wishlist
    if (isset($_POST['delete-wishlist-submit'])) {
        $wishlist_id = intval($_POST['wishlist_id']);
        $Cart->deleteWishlist($wishlist_id, $user_id); // pass user_id if required
    }

    // Move to Cart
    if (isset($_POST['move-to-cart'])) {
        $wishlist_id = intval($_POST['wishlist_id']);
        $Cart->moveToCart($wishlist_id, $user_id); // move item to cart
    }
}

// Fetch wishlist items
$wishlistItems = $Cart->getWishlist($user_id);

include('header.php');
?>

<section id="wishlist" class="py-3 mb-5">
    <div class="container-fluid w-75">
        <h5 class="font-baloo font-size-20">My Wishlist</h5>

        <div class="row">
            <div class="col-sm-9">
                <?php if (!empty($wishlistItems)): ?>
                    <?php foreach ($wishlistItems as $item): ?>
                    <div class="row border-top py-3 mt-3">
                        <div class="col-sm-2">
                            <img src="<?php echo $item['item_image'] ?? 'assets/products/1.png'; ?>" 
                                 style="height:120px;" class="img-fluid" alt="wishlist-item">
                        </div>
                        <div class="col-sm-8">
                            <h5 class="font-baloo font-size-20"><?php echo htmlentities($item['item_name']); ?></h5>
                            <small>by <?php echo htmlentities($item['item_brand'] ?? 'Brand'); ?></small>

                            <div class="qty d-flex pt-2">
                                <!-- Delete -->
                                <form method="post" class="mr-2">
                                    <input type="hidden" name="wishlist_id" value="<?php echo $item['wishlist_id']; ?>">
                                    <button type="submit" name="delete-wishlist-submit" class="btn text-danger">Delete</button>
                                </form>

                                <!-- Move to Cart -->
                                <form method="post">
                                    <input type="hidden" name="wishlist_id" value="<?php echo $item['wishlist_id']; ?>">
                                    <button type="submit" name="move-to-cart" class="btn text-primary">Move to Cart</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-sm-2 text-right">
                            <div class="font-size-20 text-danger font-baloo">
                                $<span><?php echo number_format($item['item_price'], 2); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="row border-top py-3 mt-3">
                        <div class="col-sm-12 text-center py-5">
                            <img src="assets/blog/empty_cart.png" alt="Empty Wishlist" class="img-fluid" style="height:200px;">
                            <p class="font-baloo font-size-16 text-black-50">Your wishlist is empty.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include('footer.php'); ?>
