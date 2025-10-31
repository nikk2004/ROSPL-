<?php
session_start();

// Sample product data
$products = [
    ['id' => 1, 'name' => 'iPhone 14', 'price' => 999.00, 'image' => 'assets/products/1.png', 'brand' => 'Apple'],
    ['id' => 2, 'name' => 'Galaxy S23', 'price' => 899.00, 'image' => 'assets/products/2.png', 'brand' => 'Samsung'],
    ['id' => 3, 'name' => 'Pixel 8', 'price' => 799.00, 'image' => 'assets/products/3.png', 'brand' => 'Google']
];

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart logic
if (isset($_GET['add'])) {
    $productId = intval($_GET['add']);
    $found = false;

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] === $productId) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }

    if (!$found) {
        foreach ($products as $product) {
            if ($product['id'] === $productId) {
                $product['quantity'] = 1;
                $_SESSION['cart'][] = $product;
                break;
            }
        }
    }

    // Redirect to cart page instead of same page
    header('Location: cart.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>On Sale - Mobile Shopee</title>
  <style>
    body { font-family: Arial; background: #f5f5f5; }
    .container { width: 90%; margin: 30px auto; }
    .product-grid { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
    .product-card { background: #fff; width: 250px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); padding: 15px; text-align: center; }
    .product-card img { width: 100%; height: 200px; object-fit: contain; }
    .btn { background: #007bff; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; display: inline-block; }
    .btn:hover { background: #0056b3; }
  </style>
</head>
<body>
  <div class="container">
    <h1>🔥 On Sale Products</h1>
    <div class="product-grid">
      <?php foreach ($products as $product): ?>
      <div class="product-card">
        <img src="<?= $product['image']; ?>" alt="<?= $product['name']; ?>">
        <h3><?= $product['name']; ?></h3>
        <p>Brand: <?= $product['brand']; ?></p>
        <p><strong>$<?= number_format($product['price'], 2); ?></strong></p>
        <a href="on-sale.php?add=<?= $product['id']; ?>" class="btn">Add to Cart</a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>
