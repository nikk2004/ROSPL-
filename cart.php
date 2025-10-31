<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();
include('header.php');

require_once(__DIR__ . '/database/DBController.php');
require_once(__DIR__ . '/database/Product.php');
require_once(__DIR__ . '/database/Cart.php');

$db = new DBController();
$product = new Product($db);
$Cart = new Cart($db);

$user_id = 1; // Temporary, replace with dynamic logged-in user

// Handle Delete & Save for Later
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete-cart-submit'])) {
        $cart_id = intval($_POST['cart_id']);
        $Cart->deleteCart($cart_id, $user_id);
        header("Location: cart.php");
        exit;
    }

    if (isset($_POST['wishlist-submit'])) {
        $cart_id = intval($_POST['cart_id']);
        $Cart->saveForLater($cart_id, $user_id);
        header("Location: cart.php");
        exit;
    }
}

// Fetch cart items
$cartItems = $Cart->getCart($user_id);

// Calculate subtotal using Cart class
$subtotal = $Cart->getSum($cartItems);

// 🧾 Define your free delivery threshold
$freeDeliveryThreshold = 50; // e.g., free shipping for orders above $50
$subtotalValue = floatval($subtotal);
$remaining = max(0, $freeDeliveryThreshold - $subtotalValue);

// Optional: Set delivery charge dynamically
$deliveryCharge = ($subtotalValue >= $freeDeliveryThreshold) ? 0 : 5; // $5 if under threshold
$totalWithDelivery = $subtotalValue + $deliveryCharge;
?>

<section id="cart" class="py-3 mb-5">
    <div class="container-fluid w-75">
        <h5 class="font-baloo font-size-20">Shopping Cart</h5>

        <div class="row">
            <div class="col-sm-9">
                <?php if (empty($cartItems)) { ?>
                    <div class="text-center py-5">Your cart is empty.</div>
                <?php } else { ?>
                    <?php foreach ($cartItems as $item): ?>
                    <div class="row border-top py-3 mt-3">
                        <div class="col-sm-2">
                            <img src="<?php echo $item['item_image'] ?? './assets/products/1.png'; ?>" style="height:120px;" class="img-fluid" alt="">
                        </div>
                        <div class="col-sm-8">
                            <h5 class="font-baloo font-size-20"><?php echo htmlentities($item['item_name']); ?></h5>
                            <small>by <?php echo htmlentities($item['item_brand'] ?? 'Brand'); ?></small>

                            <div class="qty d-flex pt-2">
                                <!-- Delete Button -->
                                <form method="post" class="mr-2">
                                    <input type="hidden" name="cart_id" value="<?php echo intval($item['cart_id']); ?>">
                                    <button type="submit" name="delete-cart-submit" class="btn font-baloo text-danger px-3">Delete</button>
                                </form>

                                <!-- Save for Later -->
                                <form method="post">
                                    <input type="hidden" name="cart_id" value="<?php echo intval($item['cart_id']); ?>">
                                    <button type="submit" name="wishlist-submit" class="btn font-baloo text-primary">Save for Later</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-sm-2 text-right">
                            <div class="font-size-20 text-danger font-baloo">
                                $<span class="product_price"><?php echo number_format($item['item_price'], 2); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php } ?>
            </div>

            <div class="col-sm-3">
                <div class="sub-total border text-center mt-2 p-3">
                    <?php if ($subtotalValue >= $freeDeliveryThreshold): ?>
                        <h6 class="font-size-12 font-rale text-success py-3">
                            <i class="fas fa-check"></i> Your order is eligible for <strong>FREE Delivery</strong>! 🎉
                        </h6>
                    <?php else: ?>
                        <h6 class="font-size-12 font-rale text-warning py-3">
                            <i class="fas fa-info-circle"></i>
                            Add <strong>$<?= number_format($remaining, 2) ?></strong> more to get <strong>FREE Delivery</strong> 🚚
                        </h6>
                    <?php endif; ?>

                    <div class="border-top py-4">
                        <h5 class="font-baloo font-size-20">
                            Subtotal (<?= count($cartItems) ?> items):&nbsp;
                            <span class="text-danger">$<span id="deal-price"><?= $subtotal ?></span></span>
                        </h5>

                        <h6 class="font-size-16 mt-2">
                            Delivery Charge:
                            <?php if ($deliveryCharge == 0): ?>
                                <span class="text-success">FREE</span>
                            <?php else: ?>
                                <span class="text-danger">$<?= number_format($deliveryCharge, 2) ?></span>
                            <?php endif; ?>
                        </h6>

                        <hr>
                        <h5 class="font-baloo font-size-20">
                            Total Payable: <span class="text-primary">$<?= number_format($totalWithDelivery, 2) ?></span>
                        </h5>

                        <a href="checkout.php" class="btn btn-warning mt-3 w-100">Proceed to Buy</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('footer.php'); ?>
