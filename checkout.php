<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();
include('header.php');

require_once(__DIR__ . '/database/DBController.php');
require_once(__DIR__ . '/database/Cart.php');

$db = new DBController();
$Cart = new Cart($db);

session_start();
$user_id = $_SESSION['user_id'] ?? 1;

// Fetch cart items & subtotal
$cartItems = $Cart->getCart($user_id);
$subtotal = $Cart->getSum($cartItems);

// If cart is empty
if (empty($cartItems)) {
    echo "<script>alert('Your cart is empty!'); window.location='index.php';</script>";
    exit;
}

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $zip = trim($_POST['zip']);
    $payment_method = trim($_POST['payment_method']);

    // 🧾 Create order record FIRST (for both COD and Online)
    $stmt = $db->con->prepare("INSERT INTO orders (user_id, name, email, phone, address, city, state, zip, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssssd", $user_id, $name, $email, $phone, $address, $city, $state, $zip, $payment_method, $subtotal);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    foreach ($cartItems as $item) {
        $quantity = $item['qty'] ?? 1;
        $stmt2 = $db->con->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt2->bind_param("iiid", $order_id, $item['item_id'], $quantity, $item['item_price']);
        $stmt2->execute();
    }

    if ($payment_method == "Online") {
        // ✅ Redirect to mock payment with valid order_id
        $params = http_build_query([
            'order_id' => $order_id,
            'amount' => $subtotal,
            'name' => $name,
            'email' => $email,
            'phone' => $phone
        ]);
        header("Location: mockpayment.php?$params");
        exit;
    } else {
        // ✅ COD Order placed directly
        $Cart->clearCart($user_id);
        echo "<script>alert('Order placed successfully!'); window.location='thanku.php?order_id=$order_id';</script>";
        exit;
    }
}
?>

<!-- Checkout Page HTML -->
<div class="container mt-5 mb-5">
    <h2 class="mb-4 text-center">Checkout</h2>
    <div class="row">
        <!-- Left: Form -->
        <div class="col-md-7">
            <form method="POST" action="">
                <div class="mb-3"><label class="form-label">Full Name</label><input type="text" name="name" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Address</label><textarea name="address" class="form-control" required></textarea></div>
                <div class="row">
                    <div class="col-md-4 mb-3"><label class="form-label">City</label><input type="text" name="city" class="form-control" required></div>
                    <div class="col-md-4 mb-3"><label class="form-label">State</label><input type="text" name="state" class="form-control" required></div>
                    <div class="col-md-4 mb-3"><label class="form-label">Zip</label><input type="text" name="zip" class="form-control" required></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="COD">Cash on Delivery</option>
                        <option value="Online">Online Payment</option>
                    </select>
                </div>
                <button type="submit" name="place_order" class="btn btn-success w-100 mt-3">Place Order</button>
            </form>
        </div>

        <!-- Right: Summary -->
        <div class="col-md-5">
            <div class="card p-3 shadow-sm">
                <h5>Order Summary</h5>
                <hr>
                <?php foreach($cartItems as $item): ?>
                    <p><?= htmlentities($item['item_name']) ?> × <?= $item['qty'] ?? 1 ?> = ₹<?= number_format(($item['item_price']*($item['qty'] ?? 1)),2) ?></p>
                <?php endforeach; ?>
                <hr>
                <h5>Total: ₹<?= number_format($subtotal,2) ?></h5>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
