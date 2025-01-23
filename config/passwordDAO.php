<?php
require_once 'database.php';

interface IPasswordDao
{
    public function createToken($userId);
    public function getToken($token);
    public function deleteToken($token);
    public function resetPassword($token, $newPassword);
}

class PasswordDAO implements IPasswordDao
{
    private $conn;
    private $table = 'password_resets'; // Nome da tabela

    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }

    public function createToken($userId)
    {
        $token = bin2hex(random_bytes(16));
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token válido por 1 hora

        $query = "INSERT INTO " . $this->table . " (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expires_at', $expires_at);
        $stmt->execute();

        return $token;
    }

    public function getToken($token)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE token = :token AND expires_at > NOW()";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteToken($token)
    {
        $query = "DELETE FROM " . $this->table . " WHERE token = :token";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        return $stmt->execute();
    }

    public function resetPassword($token, $newPassword)
    {
        $tokenData = $this->getToken($token);

        if ($tokenData) {
            $userId = $tokenData['user_id'];

            // Atualizar a senha do usuário
            $userDAO = new UserDAO();
            $userDAO->updatePassword($userId, $newPassword);

            // Excluir o token
            $this->deleteToken($token);

            return true;
        }

        return false;
    }
}