<?php
require_once '../config/database.php';

class Poem {
    private $title;
    private $content;
    private $visibility;
    private $authorId;
    private $categoryId;

    public function __construct($title = null, $content = null, $visibility = null, $authorId = null, $categoryId = null) {
        $this->title = $title;
        $this->content = $content;
        $this->visibility = $visibility;
        $this->authorId = $authorId;
        $this->categoryId = $categoryId;
    }

    // Getters and Setters
    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getVisibility() {
        return $this->visibility;
    }

    public function setVisibility($visibility) {
        $this->visibility = $visibility;
    }

    public function getAuthorId() {
        return $this->authorId;
    }

    public function setAuthorId($authorId) {
        $this->authorId = $authorId;
    }

    public function getCategoryId() {
        return $this->categoryId;
    }

    public function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
    }
}
?>
