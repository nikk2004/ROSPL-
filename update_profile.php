<?php
session_start();
include __DIR__ . "/database/DBController.php";  
$db = new DBController();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $stmt = $db->con->prepare("UPDATE users SET username=?, email=?, phone=?, address=? WHERE username=?");
    $stmt->bind_param("sssss", $newUsername, $email, $phone, $address, $username);

    if ($stmt->execute()) {
        $_SESSION['username'] = $newUsername;
        header("Location: account.php");
        exit();
    } else {
        echo "Update failed!";
    }
}
?>
