<?php
require_once '../model/LikeModel.php';

class LikeController {
    private $likeModel;

    public function __construct($db) {
        $this->likeModel = new LikeModel($db);
    }

    public function likePoem($user_id, $poem_id) {
        return $this->likeModel->likePoem($user_id, $poem_id);
    }
    
    public function unlikePoem($user_id, $poem_id) {
        return $this->likeModel->unlikePoem($user_id, $poem_id);
    }

    public function getAllLikedPoems($user_id) {
        // Supondo que isLiked retorne uma lista dos poemas curtidos
        return $this->likeModel->getLikedPoemsByUser($user_id);
    }    
    
}
