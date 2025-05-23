<?php
require_once 'database.php';
require_once '../model/Comment.php';

interface ICommentDao {
    public function addComment($poemId, $userId, $content);
    public function getCommentsByPoemId($poemId);
}

class CommentDAO implements ICommentDao{
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Adiciona um comentário
    public function addComment($poemId, $userId, $content) {
        try {
            $this->db->beginTransaction();

            // Insere o conteúdo do comentário
            $stmt = $this->db->prepare("INSERT INTO comments (content) VALUES (:content)");
            $stmt->bindValue(':content', $content);
            $stmt->execute();
            $commentId = $this->db->lastInsertId();

            // Associa o comentário ao poema
            $stmt = $this->db->prepare("INSERT INTO poem_comments (poem_id, comment_id, user_id) VALUES (:poemId, :commentId, :userId)");
            $stmt->bindValue(':poemId', $poemId);
            $stmt->bindValue(':commentId', $commentId);
            $stmt->bindValue(':userId', $userId);
            $stmt->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Recupera os comentários de um poema
    public function getCommentsByPoemId($poemId) {
        $stmt = $this->db->prepare("
            SELECT comments.id, comments.content, comments.created_at, poem_comments.user_id, users.name, profile.profile_picture
            FROM comments
            INNER JOIN poem_comments ON comments.id = poem_comments.comment_id
            INNER JOIN users ON poem_comments.user_id = users.id
            join profile on users.id = profile.user_id
            WHERE poem_comments.poem_id = :poemId
            ORDER BY comments.created_at DESC
        ");
        $stmt->bindValue(':poemId', $poemId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}