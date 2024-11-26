<?php
class Like {
    
    private $id;
    private $userId;
    private $poemId;
    private $liked;
    private $likedAt;

    public function __construct($userId = null, $poemId = null, $liked = null, $likedAt = null) {
        $this->userId = $userId;
        $this->poemId = $poemId;
        $this->liked = $liked;
        $this->likedAt = $likedAt;
    }

    // Getters e Setters
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

    public function getPoemId() {
        return $this->poemId;
    }

    public function setPoemId($poemId) {
        $this->poemId = $poemId;
    }

    public function getLiked() {
        return $this->liked;
    }

    public function setLiked($liked) {
        $this->liked = $liked;
    }

    public function getLikedAt() {
        return $this->likedAt;
    }

    public function setLikedAt($likedAt) {
        $this->likedAt = $likedAt;
    }
}
