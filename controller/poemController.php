<?php
require_once '../config/database.php';
require_once '../model/Poem.php';

class PoemController {
    public function validatePoem(Poem $poem) {
        if (empty($poem->getTitle()) || empty($poem->getContent())) {
            return "Título e conteúdo não podem estar vazios.";
        }
        if (!in_array($poem->getVisibility(), ['public', 'restricted'])) {
            return "Visibilidade inválida.";
        }
        return true;
    }

    public function savePoem(Poem $poem) {
        $database = new Database();
        $conn = $database->getConnection();

        $sql = "INSERT INTO poems (title, content, visibility, author_id, category_id) VALUES (:title, :content, :visibility, :author_id, :category_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':title', $poem->getTitle());
        $stmt->bindValue(':content', $poem->getContent());
        $stmt->bindValue(':visibility', $poem->getVisibility());
        $stmt->bindValue(':author_id', $poem->getAuthorId());
        $stmt->bindValue(':category_id', $poem->getCategoryId());
        return $stmt->execute();
    }

    public function getAllPoems() {
        $database = new Database();
        $conn = $database->getConnection();
    
        $sql = "SELECT poems.*, categories.name as category_name, users.username, profile.profile_picture
                FROM poems
                JOIN categories ON poems.category_id = categories.id
                JOIN users ON poems.author_id = users.id
                LEFT JOIN profile ON users.id = profile.user_id
                WHERE visibility = 'public'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    

    public function getPoemsByCategory($categoryId) {
        $database = new Database();
        $conn = $database->getConnection();

        $sql = "SELECT poems.*, categories.name as category_name FROM poems
                JOIN categories ON poems.category_id = categories.id
                WHERE category_id = :category_id AND visibility = 'public'";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPoemsByUser($userId) {
        $database = new Database();
        $conn = $database->getConnection();

        $sql = "SELECT poems.*, categories.name as category_name FROM poems
                JOIN categories ON poems.category_id = categories.id
                WHERE author_id = :author_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':author_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategories() {
        $database = new Database();
        $conn = $database->getConnection();

        $sql = "SELECT * FROM categories";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para buscar poemas por palavra-chave
    public function searchPoems($keyword) {
        $database = new Database();
        $conn = $database->getConnection();

        $sql = "SELECT * FROM poems WHERE title LIKE :keyword OR content LIKE :keyword";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':keyword', '%' . $keyword . '%');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
