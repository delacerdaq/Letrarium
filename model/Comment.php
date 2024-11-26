<?php
class Comment {
    private $id;
    private $poemId;
    private $userId;
    private $content;
    private $createdAt;

    // Construtor para inicializar os atributos
    public function __construct($poemId = null, $userId = null, $content = null, $createdAt = null) {
        $this->poemId = $poemId;
        $this->userId = $userId;
        $this->content = $content;
        $this->createdAt = $createdAt;
    }

    // Getters e Setters
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getPoemId() {
        return $this->poemId;
    }

    public function setPoemId($poemId) {
        $this->poemId = $poemId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }
}
?>
