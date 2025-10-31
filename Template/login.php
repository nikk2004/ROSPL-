<?php
session_start();
include('../database/DBController.php');
$db = new DBController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST["email"];
    $password = $_POST["password"];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $db->con->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            header("Location: ../index.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No user found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Mobile Shopee</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(120deg, #a8ff78, #78ffd6);
        }
        .login-container {
            background: white;
            padding: 40px 35px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            width: 400px;
            max-width: 90%;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            font-weight: 600;
        }
        .login-container form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 10px;
            border: 1px solid #ddd;
            font-size: 14px;
            transition: 0.3s;
        }
        .login-container form input:focus {
            border-color: #34c3eb;
            box-shadow: 0 0 8px rgba(52,195,235,0.4);
            outline: none;
        }
        .login-container form button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(45deg, #34ebba, #34c3eb);
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        .login-container form button:hover {
            background: linear-gradient(45deg, #34c3eb, #34ebba);
        }
        .error {
            color: red;
            margin-bottom: 10px;
            text-align: center;
        }
        .signup-link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        .signup-link a {
            color: #34c3eb;
            text-decoration: none;
            transition: 0.3s;
        }
        .signup-link a:hover {
            color: #34ebba;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>

    <?php if(isset($error)) { echo "<p class='error'>$error</p>"; } ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <div class="signup-link">
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</div>

</body>
</html>
