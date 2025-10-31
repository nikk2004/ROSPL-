<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome - Mobile Shopee</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 100px; }
        a { text-decoration: none; color: white; background-color: #f44336; padding: 10px 20px; border-radius: 5px; }
        a:hover { background-color: #d32f2f; }
    </style>
</head>
<body>

<h2>Welcome, <?php echo $_SESSION['username']; ?> 🎉</h2>
<a href="logout.php">Logout</a>

</body>
</html>
