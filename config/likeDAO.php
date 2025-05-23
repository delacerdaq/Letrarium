<?php
require_once 'database.php';
require_once '../model/tag.php';

interface ILikeDao {
    public function likePoem($user_id, $poem_id);
    public function unlikePoem($user_id, $poem_id);
    public function getLikedPoemsByUser($user_id);
    public function hasLiked($poemId, $userId);
    public function countLikes($poemId);
}

class LikeDAO implements ILikeDao{
   
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function likePoem($user_id, $poem_id) {
        $query = "INSERT INTO likes (user_id, poem_id) VALUES (:user_id, :poem_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':poem_id', $poem_id);
    
        return $stmt->execute();
    }
    
    public function unlikePoem($user_id, $poem_id) {
        $query = "DELETE FROM likes WHERE user_id = :user_id AND poem_id = :poem_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':poem_id', $poem_id);
    
        return $stmt->execute();
    }

    public function getLikedPoemsByUser($user_id) {
        $query = "SELECT poem_id FROM likes WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    
        $likedPoems = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $likedPoems[] = $row['poem_id'];
        }
    
        return $likedPoems;
    }

    public function hasLiked($poemId, $userId) {
        $query = "SELECT * FROM likes WHERE poem_id = ? AND user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$poemId, $userId]);
        return $stmt->fetch() ? true : false;
    }

    public function countLikes($poemId) {
        $query = "SELECT COUNT(*) AS total_likes FROM likes WHERE poem_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$poemId]);
        $result = $stmt->fetch();
        return $result['total_likes'];
    }
}

?>