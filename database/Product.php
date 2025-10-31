<?php
class Product {
    public $db = null;
    protected $userId = 1; // temporary test user

    public function __construct($db) {
        if (!isset($db->con)) return null;
        $this->db = $db;
    }

    // Get all records from a table
    public function getData($table = "products") {
        $mysqli = $this->db->con;
        $data = [];

        switch ($table) {
            case 'products':
                $res = $mysqli->query("SELECT * FROM products");
                if ($res) $data = $res->fetch_all(MYSQLI_ASSOC);
                break;

            case 'cart':
                $sql = "SELECT c.cart_id, c.user_id, c.item_id, p.item_name, p.item_price, p.item_image, p.item_brand
                        FROM cart c
                        JOIN products p ON c.item_id = p.item_id
                        WHERE c.user_id = {$this->userId}";
                $res = $mysqli->query($sql);
                if ($res) $data = $res->fetch_all(MYSQLI_ASSOC);
                break;

            case 'wishlist':
                $sql = "SELECT w.*, p.item_name, p.item_price, p.item_image, p.item_brand
                        FROM wishlist w
                        JOIN products p ON w.item_id = p.item_id
                        WHERE w.user_id = {$this->userId}";
                $res = $mysqli->query($sql);
                if ($res) $data = $res->fetch_all(MYSQLI_ASSOC);
                break;

            default:
                $data = [];
        }

        return $data;
    }

    // Get a single product by ID
    public function getProduct($item_id) {
        $item_id = intval($item_id);
        $res = $this->db->con->query("SELECT * FROM products WHERE item_id = {$item_id} LIMIT 1");
        return $res ? $res->fetch_assoc() : null;
    }
}
?>
