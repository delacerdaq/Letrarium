<?php
require_once '../config/database.php';

class Profile {
    private $id;
    private $userId;
    private $profilePicture;
    private $bio;

    public function __construct($userId, $profilePicture = null, $bio = null) {
        $this->userId = $userId;
        $this->profilePicture = $profilePicture;
        $this->bio = $bio;
    }

    // Getters and Setters
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function getProfilePicture() {
        return $this->profilePicture;
    }

    public function setProfilePicture($profilePicture) {
        $this->profilePicture = $profilePicture;
    }

    public function getBio() {
        return $this->bio;
    }

    public function setBio($bio) {
        $this->bio = $bio;
    }

    // Fetch profile by user ID
    public static function fetchProfileByUserId($userId) {
        $database = new Database();
        $conn = $database->getConnection();
    
        $sql = "SELECT * FROM profile WHERE user_id = :user_id";
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
            $database = new Database();
            $conn = $database->getConnection();
    
            $sql = "UPDATE profile SET bio = :bio, profile_picture = :profile_picture WHERE user_id = :user_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':bio', $data['bio']);
            $stmt->bindValue(':profile_picture', $data['profile_picture']);
            $stmt->bindValue(':user_id', $userId);
    
            return $stmt->execute();
        }

    // Update profile picture
    public function updateProfilePicture($userId, $profilePicture) {
        $database = new Database();
        $conn = $database->getConnection();

        $sql = "UPDATE profile SET profile_picture = :profile_picture WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':profile_picture', $profilePicture);
        $stmt->bindValue(':user_id', $userId);

        return $stmt->execute();
    }

    // Update bio
    public function updateBio($userId, $bio) {
        $database = new Database();
        $conn = $database->getConnection();

        $sql = "UPDATE profile SET bio = :bio WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':bio', $bio);
        $stmt->bindValue(':user_id', $userId);

        return $stmt->execute();
    }

    // Cria uma nova entrada de perfil se ela não existir
    public function createProfile($userId, $profilePicture = null, $bio = null) {
        $database = new Database();
        $conn = $database->getConnection();

        $sql = "INSERT INTO profile (user_id, profile_picture, bio) VALUES (:user_id, :profile_picture, :bio)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':profile_picture', $profilePicture);
        $stmt->bindValue(':bio', $bio);

        return $stmt->execute();
    }
}
?>
