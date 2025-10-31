<?php
session_start();
include('header.php');

// Optionally, you can get order_id from URL
$order_id = $_GET['order_id'] ?? '';
?>

<div class="container mt-5 mb-5 text-center">
    <div class="card p-5 shadow-sm">
        <h2 class="text-success">Thank You!</h2>
        <p>Your order has been placed successfully.</p>
        <?php if($order_id): ?>
            <p><strong>Order ID:</strong> <?= htmlentities($order_id) ?></p>
        <?php endif; ?>
        <a href="index.php" class="btn btn-primary mt-3">Continue Shopping</a>
    </div>
</div>

<?php include('footer.php'); ?>
