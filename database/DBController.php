<?php
class DBController {
    protected $host = 'localhost';
    protected $user = 'root';
    protected $password = '';
    protected $database = "shopee";

    public $con = null;

    public function __construct() {
        $this->con = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        if (!$this->con){
            die("Database connection failed: " . mysqli_connect_error());
        }
        // set charset
        $this->con->set_charset("utf8mb4");
    }

    public function __destruct() {
        $this->closeConnection();
    }

    protected function closeConnection() {
        if ($this->con != null) {
            $this->con->close();
            $this->con = null;
        }
    }
}
?>
