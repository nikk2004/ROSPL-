<?php
session_start();
require_once(__DIR__ . '/database/DBController.php');

$db = new DBController();

// Assuming user login is already done
$user_id = $_SESSION['user_id'] ?? 1; // Replace with actual logged-in user ID

// Fetch existing profile (if any)
$stmt = $db->con->prepare("SELECT * FROM user_profiles WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$profile = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $zip = trim($_POST['zip']);

    // Handle image upload
    $profile_image = $profile['profile_image'] ?? null;
    if (!empty($_FILES['profile_image']['name'])) {
        $targetDir = "uploads/";
        $fileName = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        // Move uploaded file
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFilePath)) {
            $profile_image = $targetFilePath;
        }
    }

    // If profile exists -> UPDATE, else -> INSERT
    if ($profile) {
        $stmt = $db->con->prepare("UPDATE user_profiles 
            SET full_name=?, phone=?, address=?, city=?, state=?, zip=?, profile_image=? 
            WHERE user_id=?");
        $stmt->bind_param("sssssssi", $full_name, $phone, $address, $city, $state, $zip, $profile_image, $user_id);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $db->con->prepare("INSERT INTO user_profiles 
            (user_id, full_name, phone, address, city, state, zip, profile_image) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssss", $user_id, $full_name, $phone, $address, $city, $state, $zip, $profile_image);
        $stmt->execute();
        $stmt->close();
    }

    echo "<script>alert('Profile updated successfully!'); window.location='profile.php';</script>";
    exit;
}
?>

<?php include('header.php'); ?>

<div class="container mt-5 mb-5">
    <h2 class="text-center mb-4">My Profile</h2>

    <div class="card p-4 shadow-sm mx-auto" style="max-width: 600px;">
        <form method="POST" enctype="multipart/form-data">
            <div class="text-center mb-3">
                <img src="<?php echo !empty($profile['profile_image']) ? $profile['profile_image'] : 'assets/default-avatar.png'; ?>" 
                     class="rounded-circle" width="120" height="120" alt="Profile Image">
                <div class="mt-2">
                    <input type="file" name="profile_image" accept="image/*">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control" 
                       value="<?php echo htmlspecialchars($profile['full_name'] ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" 
                       value="<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control"><?php echo htmlspecialchars($profile['address'] ?? ''); ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" 
                           value="<?php echo htmlspecialchars($profile['city'] ?? ''); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">State</label>
                    <input type="text" name="state" class="form-control" 
                           value="<?php echo htmlspecialchars($profile['state'] ?? ''); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Zip</label>
                    <input type="text" name="zip" class="form-control" 
                           value="<?php echo htmlspecialchars($profile['zip'] ?? ''); ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-success w-100">Save Profile</button>
        </form>
    </div>
</div>

<?php include('footer.php'); ?>
