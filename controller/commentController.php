<?php
require_once '../config/commentDAO.php';

class CommentController {
    private $commentDAO;

    public function __construct() {
        $this->commentDAO = new CommentDAO();
    }

    public function addComment($poemId, $userId, $content) {
        return $this->commentDAO->addComment($poemId, $userId, $content);
    }

    public function getCommentsByPoemId($poemId) {
        return $this->commentDAO->getCommentsByPoemId($poemId);
    }
}
?>
