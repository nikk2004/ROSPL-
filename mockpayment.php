<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('header.php');
require_once(__DIR__ . '/database/DBController.php');
session_start();

$amount = $_GET['amount'] ?? 0;
$name = $_GET['name'] ?? '';
$email = $_GET['email'] ?? '';
$phone = $_GET['phone'] ?? '';
$address = $_GET['address'] ?? '';
$city = $_GET['city'] ?? '';
$state = $_GET['state'] ?? '';
$zip = $_GET['zip'] ?? '';
$user_id = $_SESSION['user_id'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'] ?? 'Online';
    $upi_or_card = $_POST['upi_or_card'] ?? '';

    $db = new DBController();

    // Simulate successful payment
    $success = true;

    if ($success) {
        // Insert order details into orders table
        $stmt = $db->con->prepare("INSERT INTO orders (user_id, name, email, phone, address, city, state, zip, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssssd", $user_id, $name, $email, $phone, $address, $city, $state, $zip, $payment_method, $amount);
        $stmt->execute();
        $order_id = $stmt->insert_id;

        // Insert order items from user's cart
        $result = $db->con->query("SELECT * FROM cart WHERE user_id = $user_id");
        while ($row = $result->fetch_assoc()) {
            $product_id = $row['product_id'] ?? 0;
            $price = $row['price'] ?? 0;
            $quantity = $row['quantity'] ?? 1;

            if ($product_id > 0 && $quantity > 0) {
                $stmt2 = $db->con->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt2->bind_param("iiid", $order_id, $product_id, $quantity, $price);
                $stmt2->execute();
            }
        }

        // Clear user's cart
        $db->con->query("DELETE FROM cart WHERE user_id = $user_id");

        // Redirect to thank you page
        echo "<script>
            alert('Payment of ₹$amount via $payment_method was successful!');
            window.location='thanku.php?order_id=$order_id';
        </script>";
        exit;
    } else {
        echo "<script>alert('Payment failed! Please try again.'); window.location='checkout.php';</script>";
        exit;
    }
}
?>

<section class="mock-payment py-5">
    <div class="container w-50">
        <h4 class="mb-4 text-center">Mock Online Payment</h4>
        <form method="post">
            <input type="hidden" name="amount" value="<?= htmlspecialchars($amount); ?>">

            <div class="form-group mb-3">
                <label>Amount</label>
                <input type="text" class="form-control" value="₹<?= htmlspecialchars($amount); ?>" readonly>
            </div>

            <div class="form-group mb-3">
                <label>Payment Method</label>
                <select name="payment_method" class="form-control" required>
                    <option value="PhonePe">PhonePe</option>
                    <option value="GooglePay">Google Pay</option>
                    <option value="Paytm">Paytm</option>
                    <option value="CreditCard">Credit / Debit Card</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label>UPI ID / Card Number</label>
                <input type="text" name="upi_or_card" class="form-control" placeholder="e.g. 9999999999@upi or 1234 5678 9012 3456" required>
            </div>

            <button type="submit" class="btn btn-success mt-3 w-100">Pay Now</button>
        </form>
    </div>
</section>

<?php include('footer.php'); ?>
