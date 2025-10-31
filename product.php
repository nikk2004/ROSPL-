<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();

include('header.php');

// Include classes
require_once(__DIR__ . '/database/DBController.php');
require_once(__DIR__ . '/database/Product.php');
require_once(__DIR__ . '/database/Cart.php');

// Initialize objects
$db = new DBController();
$product = new Product($db);
$Cart = new Cart($db);

// Temporary logged-in user
$user_id = 1;

// Get item_id from URL
$item_id = intval($_GET['item_id'] ?? 0);

// Fetch product details
$product_detail = $product->getProduct($item_id);

// Fetch all products for Top Sale
$product_shuffle = $product->getData('products');
shuffle($product_shuffle);

// Get items already in cart and wishlist
$in_cart = $Cart->getCartId($Cart->getCart($user_id)) ?? [];
$wishlistItems = $Cart->getWishlist($user_id);
$in_wishlist = $Cart->getCartId($wishlistItems) ?? [];

// ---------------- Handle POST Actions ----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id_post = intval($_POST['item_id'] ?? 0);

    if ($item_id_post > 0) {

        // Add to Cart
        if (isset($_POST['add_to_cart'])) {
            $Cart->addToCart($item_id_post, $user_id);
            header("Location: " . $_SERVER['PHP_SELF'] . "?item_id=" . $item_id);
            exit;
        }

        // Add to Wishlist
        if (isset($_POST['add_to_wishlist'])) {
            $Cart->addToWishlist($item_id_post, $user_id);
            header("Location: wishlist.php"); // redirect to wishlist page
            exit;
        }

        // Buy Now
        if (isset($_POST['buy_now'])) {
            $Cart->addToCart($item_id_post, $user_id);
            header("Location: checkout.php");
            exit;
        }
    }
}
?>

<main class="container py-5">

    <!-- Product Detail -->
    <div class="row">
        <div class="col-md-6">
            <img src="<?php echo $product_detail['item_image'] ?? './assets/products/1.png'; ?>" 
                 alt="product" class="img-fluid mb-3">

            <div class="form-row">
                <!-- Buy Now -->
                <div class="col mb-2">
                    <form method="post">
                        <input type="hidden" name="item_id" value="<?php echo intval($product_detail['item_id'] ?? 0); ?>">
                        <button type="submit" name="buy_now" class="btn btn-danger form-control">
                            Proceed to Buy
                        </button>
                    </form>
                </div>

                <!-- Add to Cart -->
                <div class="col mb-2">
                    <form method="post">
                        <input type="hidden" name="item_id" value="<?php echo intval($product_detail['item_id'] ?? 0); ?>">
                        <button type="submit" name="add_to_cart" class="btn btn-warning form-control"
                            <?php echo in_array(intval($product_detail['item_id'] ?? 0), $in_cart) ? 'disabled' : ''; ?>>
                            <?php echo in_array(intval($product_detail['item_id'] ?? 0), $in_cart) ? 'In Cart' : 'Add to Cart'; ?>
                        </button>
                    </form>
                </div>

                <!-- Add to Wishlist -->
                <div class="col mb-2">
                    <form method="post">
                        <input type="hidden" name="item_id" value="<?php echo intval($product_detail['item_id'] ?? 0); ?>">
                        <button type="submit" name="add_to_wishlist" class="btn btn-outline-danger form-control"
                            <?php echo in_array(intval($product_detail['item_id'] ?? 0), $in_wishlist) ? 'disabled' : ''; ?>>
                            <?php echo in_array(intval($product_detail['item_id'] ?? 0), $in_wishlist) ? 'In Wishlist' : 'Add to Wishlist'; ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 py-3">
            <h5><?php echo $product_detail['item_name'] ?? 'Unknown Product'; ?></h5>
            <small>by <?php echo $product_detail['item_brand'] ?? 'Unknown Brand'; ?></small>

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
            <table class="my-3">
                <tr>
                    <td>M.R.P:</td>
                    <td><strike>$<?php echo number_format($product_detail['item_mrp'] ?? 0, 2); ?></strike></td>
                </tr>
                <tr>
                    <td>Deal Price:</td>
                    <td class="text-danger">$<?php echo number_format($product_detail['item_price'] ?? 0, 2); ?>
                        <small class="text-dark">&nbsp;&nbsp;Inclusive of all taxes</small>
                    </td>
                </tr>
                <tr>
                    <td>You Save:</td>
                    <td>$<?php echo number_format(($product_detail['item_mrp'] ?? 0) - ($product_detail['item_price'] ?? 0), 2); ?></td>
                </tr>
            </table>

            <hr>
            <h6>Product Description</h6>
            <p><?php echo $product_detail['item_description'] ?? 'No description available.'; ?></p>
        </div>
    </div>

    <!-- Top Sale Section -->
    <section id="top-sale">
        <div class="container py-5">
            <h4 class="font-rubik font-size-20">Top Sale</h4>
            <hr>
            <div class="owl-carousel owl-theme">
                <?php foreach ($product_shuffle as $item): ?>
                    <div class="item py-2">
                        <div class="product font-rale text-center">
                            <a href="product.php?item_id=<?php echo $item['item_id']; ?>">
                                <img src="<?php echo $item['item_image'] ?? 'assets/products/1.png'; ?>" 
                                     alt="<?php echo htmlentities($item['item_name']); ?>" 
                                     style="height:200px; object-fit:contain;" class="img-fluid">
                            </a>
                            <h6 class="mt-2"><?php echo htmlentities($item['item_name']); ?></h6>
                            <div class="price py-2">$<?php echo number_format($item['item_price'] ?? 0, 2); ?></div>

                            <!-- Add to Cart -->
                            <form method="post" class="mb-1">
                                <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                                <button type="submit" name="add_to_cart" class="btn btn-warning w-75"
                                    <?php echo in_array($item['item_id'], $in_cart) ? 'disabled' : ''; ?>>
                                    <?php echo in_array($item['item_id'], $in_cart) ? 'In Cart' : 'Add to Cart'; ?>
                                </button>
                            </form>

                            <!-- Add to Wishlist -->
                            <form method="post">
                                <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                                <button type="submit" name="add_to_wishlist" class="btn btn-outline-danger w-75"
                                    <?php echo in_array($item['item_id'], $in_wishlist) ? 'disabled' : ''; ?>>
                                    <?php echo in_array($item['item_id'], $in_wishlist) ? 'In Wishlist' : 'Add to Wishlist'; ?>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

</main>

<?php include('footer.php'); ?>

<!-- JS Scripts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script>
$(document).ready(function(){
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
});
</script>
