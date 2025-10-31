<?php
session_start();
include __DIR__ . "/database/DBController.php";
$db = new DBController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'] ?? "Guest";
    $message = $_POST['message'];

    $stmt = $db->con->prepare("INSERT INTO feedback (username, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $message);

    if ($stmt->execute()) {
        echo "Thank you for your feedback!";
        echo "<br><a href='account.php'>Back to Account</a>";
    } else {
        echo "Failed to submit feedback!";
    }
}
?>
