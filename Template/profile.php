<?php
session_start();
include "../database/DBController.php";  
$db = new DBController();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch user details from DB
$stmt = $db->con->prepare("SELECT * FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile - Mobile Shopee</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(120deg, #a8ff78, #78ffd6);
            margin: 0;
            padding: 0;
        }
        .profile-container {
            width: 500px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .profile-info {
            margin-bottom: 15px;
        }
        .profile-info label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .profile-info p {
            margin: 0;
            color: #555;
        }
        .logout-btn {
            display: block;
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            text-align: center;
            background: linear-gradient(45deg, #34ebba, #34c3eb);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
            transition: 0.3s;
        }
        .logout-btn:hover {
            background: linear-gradient(45deg, #34c3eb, #34ebba);
        }
    </style>
</head>
<body>

<div class="profile-container">
    <h2>My Profile</h2>

    <div class="profile-info">
        <label>Username:</label>
        <p><?php echo htmlspecialchars($user['username']); ?></p>
    </div>

    <div class="profile-info">
        <label>Email:</label>
        <p><?php echo htmlspecialchars($user['email']); ?></p>
    </div>

    <?php if (!empty($user['phone'])): ?>
    <div class="profile-info">
        <label>Phone:</label>
        <p><?php echo htmlspecialchars($user['phone']); ?></p>
    </div>
    <?php endif; ?>

    <?php if (!empty($user['address'])): ?>
    <div class="profile-info">
        <label>Address:</label>
        <p><?php echo htmlspecialchars($user['address']); ?></p>
    </div>
    <?php endif; ?>

    <a class="logout-btn" href="logout.php">Logout</a>
</div>

</body>
</html>
