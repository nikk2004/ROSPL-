<?php
class User {
    private $db = null;

    public function __construct($db) {
        if (!isset($db->con)) return null;
        $this->db = $db;
    }

    // Get user details by ID
    public function getUser($user_id) {
        $mysqli = $this->db->con;
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    // Update user info
    public function updateUser($user_id, $name, $email, $phone, $address) {
        $mysqli = $this->db->con;
        $stmt = $mysqli->prepare("UPDATE users SET name=?, email=?, phone=?, address=? WHERE user_id=?");
        $stmt->bind_param("ssssi", $name, $email, $phone, $address, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}
?>
