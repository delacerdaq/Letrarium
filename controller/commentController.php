<?php
require_once '../model/Comment.php';

class CommentController {
    private $commentModel;

    public function __construct() {
        $this->commentModel = new Comment();
    }

    public function addComment($poemId, $userId, $content) {
        return $this->commentModel->addComment($poemId, $userId, $content);
    }

    public function getCommentsByPoemId($poemId) {
        return $this->commentModel->getCommentsByPoemId($poemId);
    }
}
?>
