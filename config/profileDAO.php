<?php
require_once 'database.php';
require_once '../model/profile.php';

interface IProfileDao{
    public static function fetchProfileByUserId($userId);
    public function updateProfile($userId, $data);
    public function updateProfilePicture($userId, $profilePicture);
    public function updateBio($userId, $bio);
    public function createProfile($userId, $profilePicture = null, $bio = null);
}

class ProfileDAO implements IProfileDao{

    private $conn;
    private $table = 'profile';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public static function fetchProfileByUserId($userId) {
        $sql = "SELECT * FROM profile WHERE user_id = :user_id";
        $conn = (new Database())->getConnection(); 
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':user_id', $userId);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result === false) {
            // Handle error or log it
            return [];
        }
    
        return $result;
        }
    
    // Update profile
    public function updateProfile($userId, $data) {
        $sql = "UPDATE profile SET bio = :bio, profile_picture = :profile_picture WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':bio', $data['bio']);
        $stmt->bindValue(':profile_picture', $data['profile_picture']);
        $stmt->bindValue(':user_id', $userId);
    
        return $stmt->execute();
    }

    // Update profile picture
    public function updateProfilePicture($userId, $profilePicture) {
        $sql = "UPDATE profile SET profile_picture = :profile_picture WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':profile_picture', $profilePicture);
        $stmt->bindValue(':user_id', $userId);

        return $stmt->execute();
    }

    // Update bio
    public function updateBio($userId, $bio) {
        $sql = "UPDATE profile SET bio = :bio WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':bio', $bio);
        $stmt->bindValue(':user_id', $userId);

        return $stmt->execute();
    }

    // Cria uma nova entrada de perfil se ela não existir
    public function createProfile($userId, $profilePicture = null, $bio = null) {
        $sql = "INSERT INTO profile (user_id, profile_picture, bio) VALUES (:user_id, :profile_picture, :bio)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':profile_picture', $profilePicture);
        $stmt->bindValue(':bio', $bio);

        return $stmt->execute();
    }

}

?>