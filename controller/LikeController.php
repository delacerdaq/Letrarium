<?php
require_once '../config/LikeDAO.php';

class LikeController {
    private $likeDAO;

    public function __construct($db) {
        $this->likeDAO = new LikeDAO();
    }

    public function likePoem($user_id, $poem_id) {
        return $this->likeDAO->likePoem($user_id, $poem_id);
    }
    
    public function unlikePoem($user_id, $poem_id) {
        return $this->likeDAO->unlikePoem($user_id, $poem_id);
    }

    public function getAllLikedPoems($user_id) {
        // Supondo que isLiked retorne uma lista dos poemas curtidos
        return $this->likeDAO->getLikedPoemsByUser($user_id);
    }    
    
}
