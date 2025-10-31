<?php
class Cart {
    private $db = null;

    public function __construct($db) {
        if (!isset($db->con)) return null;
        $this->db = $db;
    }

    // --------------------------
    // 🛒 Add item to Cart
    // --------------------------
    public function addToCart($item_id, $user_id = 1) {
        $mysqli = $this->db->con;

        // Prevent duplicates
        $stmtCheck = $mysqli->prepare("SELECT * FROM cart WHERE user_id=? AND item_id=?");
        $stmtCheck->bind_param("ii", $user_id, $item_id);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows == 0) {
            $stmt = $mysqli->prepare("INSERT INTO cart(user_id,item_id) VALUES(?,?)");
            $stmt->bind_param("ii", $user_id, $item_id);
            $stmt->execute();
            $stmt->close();
        }

        $stmtCheck->close();
    }

    // --------------------------
    // 💖 Add item to Wishlist
    // --------------------------
    public function addToWishlist($item_id, $user_id = 1) {
        $mysqli = $this->db->con;

        // Prevent duplicates
        $stmtCheck = $mysqli->prepare("SELECT * FROM wishlist WHERE user_id=? AND item_id=?");
        $stmtCheck->bind_param("ii", $user_id, $item_id);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows == 0) {
            $stmt = $mysqli->prepare("INSERT INTO wishlist(user_id,item_id) VALUES(?,?)");
            $stmt->bind_param("ii", $user_id, $item_id);
            $stmt->execute();
            $stmt->close();
        }

        $stmtCheck->close();
    }

    // --------------------------
    // ❌ Delete from Cart
    // --------------------------
    public function deleteCart($cart_id, $user_id = 1) {
        $stmt = $this->db->con->prepare("DELETE FROM cart WHERE cart_id=? AND user_id=?");
        $stmt->bind_param("ii", $cart_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }

    // --------------------------
    // 🗑️ Delete from Wishlist
    // --------------------------
    public function deleteWishlist($wishlist_id, $user_id = 1) {
        $stmt = $this->db->con->prepare("DELETE FROM wishlist WHERE wish_id=? AND user_id=?");
        $stmt->bind_param("ii", $wishlist_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }

    // --------------------------
    // 🔁 Move Cart -> Wishlist
    // --------------------------
    public function saveForLater($cart_id, $user_id = 1) {
        $mysqli = $this->db->con;

        // Get item_id
        $stmt = $mysqli->prepare("SELECT item_id FROM cart WHERE cart_id=? AND user_id=?");
        $stmt->bind_param("ii", $cart_id, $user_id);
        $stmt->execute();
        $stmt->bind_result($item_id);
        $stmt->fetch();
        $stmt->close();

        if ($item_id) {
            $this->addToWishlist($item_id, $user_id); // Add to wishlist
            $this->deleteCart($cart_id, $user_id);    // Remove from cart
        }
    }

    // --------------------------
    // 🔁 Move Wishlist -> Cart
    // --------------------------
    public function moveToCart($wishlist_id, $user_id = 1) {
        $mysqli = $this->db->con;

        // Get item_id
        $stmt = $mysqli->prepare("SELECT item_id FROM wishlist WHERE wish_id=? AND user_id=?");
        $stmt->bind_param("ii", $wishlist_id, $user_id);
        $stmt->execute();
        $stmt->bind_result($item_id);
        $stmt->fetch();
        $stmt->close();

        if ($item_id) {
            $this->addToCart($item_id, $user_id);       // Add to cart
            $this->deleteWishlist($wishlist_id, $user_id); // Remove from wishlist
        }
    }

    // --------------------------
    // 🧾 Get Cart Items
    // --------------------------
    public function getCart($user_id = 1) {
        $stmt = $this->db->con->prepare("
            SELECT c.cart_id, p.item_id, p.item_name, p.item_price, p.item_image, p.item_brand
            FROM cart c
            JOIN products p ON c.item_id=p.item_id
            WHERE c.user_id=?
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    // --------------------------
    // 🧾 Get Wishlist Items
    // --------------------------
    public function getWishlist($user_id = 1) {
        $stmt = $this->db->con->prepare("
            SELECT w.wish_id AS wishlist_id, p.item_id, p.item_name, p.item_price, p.item_image, p.item_brand
            FROM wishlist w
            JOIN products p ON w.item_id=p.item_id
            WHERE w.user_id=?
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    // --------------------------
    // 💲 Calculate total
    // --------------------------
    public function getSum($cartArray = null) {
        $sum = 0;
        if (!empty($cartArray) && is_array($cartArray)) {
            foreach ($cartArray as $item) {
                $sum += floatval($item['item_price'] ?? 0);
            }
        }
        return number_format($sum, 2, '.', '');
    }

    // --------------------------
    // 📦 Get array of item_ids
    // --------------------------
        public function getCartId($cartArray = null, $key="item_id") {
        if (!empty($cartArray) && is_array($cartArray)) {
            return array_map(function($value) use ($key){
                return intval($value[$key]);
            }, $cartArray);
        }
        return [];
    }

    // --------------------------
    // 🧹 Clear entire Cart after order
    // --------------------------
    public function clearCart($user_id = 1) {
        $stmt = $this->db->con->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }
}
?>


