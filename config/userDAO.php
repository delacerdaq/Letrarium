<?php
require_once 'database.php';

class UserDAO {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function register($username, $name, $email, $password, $terms) {
        $sql = "INSERT INTO users (username, name, email, password, terms) VALUES (:username, :name, :email, :password, :terms)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
        $stmt->bindValue(':terms', $terms, PDO::PARAM_BOOL);
        $stmt->execute();
    }
}
?>
