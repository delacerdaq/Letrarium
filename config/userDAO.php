<?php
require_once 'database.php';
require_once '../model/user.php';

class UserDAO {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function register($username, $name, $email, $password, $terms) {
        $sql = "INSERT INTO " . $this->table . "(username, name, email, password, terms) VALUES (:username, :name, :email, :password, :terms)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
        $stmt->bindValue(':terms', $terms, PDO::PARAM_BOOL);
        
        try{
            return $stmt->execute();
        } 
        catch (PDOException $e) {
            return false; 
        }
    }

    public function validateUser($username, $password) {
        $sql = "SELECT * FROM " . $this->table . " WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    
    /*
    // Método para obter o perfil do usuário pelo ID
    public function getUserById($userId) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $userId);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user) {
            return new User(
                $user['username'],
                $user['name'],
                $user['email'],
                $user['password'],
                $user['terms']
            );
        }
        return null;
    }
    */

    public function getUserByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePassword($id, $password) {
        $query = "UPDATE " . $this->table . " SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);
    
        // Crie uma variável para armazenar a senha criptografada
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
        // Use bindParam para passar a variável
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
