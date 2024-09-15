<?php
require_once '../config/database.php';

class Poem {
    private $id;
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

    public function getId() {
        return $this->id;
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

    // CRUD Methods
    public function save() {
        $database = new Database();
        $conn = $database->getConnection();
        $sql = "INSERT INTO poems (title, content, visibility, author_id, category_id) VALUES (:title, :content, :visibility, :authorId, :categoryId)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':title', $this->title);
        $stmt->bindValue(':content', $this->content);
        $stmt->bindValue(':visibility', $this->visibility);
        $stmt->bindValue(':authorId', $this->authorId);
        $stmt->bindValue(':categoryId', $this->categoryId);
        
        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId(); // Defina o ID do poema
            return true;
        }
        return false;
    }

    public function editPoem($poemId, $title, $content, $categoryId, $visibility) {
        try {
            $database = new Database();
            $conn = $database->getConnection();
    
            // Atualiza o poema
            $sql = "UPDATE poems 
                      SET title = :title, content = :content, category_id = :categoryId, visibility = :visibility 
                      WHERE id = :poemId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
            $stmt->bindParam(':visibility', $visibility);
            $stmt->bindParam(':poemId', $poemId, PDO::PARAM_INT);
    
            $result = $stmt->execute();
    
            // Chama o método para atualizar as tags
            if ($result) {
                $tagModel = new Tag();
                if (isset($tags) && is_array($tags)) {
                    return $tagModel->editTags($poemId, $tags);
                }
            }
    
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Erro ao editar o poema: " . $e->getMessage());
        }
    }    

    public function getById($poemId) {
        try {
            $database = new Database();
            $conn = $database->getConnection();
    
            $sql = "SELECT p.id, p.title, p.content, p.category_id, p.visibility, GROUP_CONCAT(t.name SEPARATOR ', ') as tags
                    FROM poems p
                    LEFT JOIN poem_tags pt ON p.id = pt.poem_id
                    LEFT JOIN tags t ON pt.tag_id = t.id
                    WHERE p.id = :poemId
                    GROUP BY p.id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':poemId', $poemId, PDO::PARAM_INT);
            $stmt->execute();
    
            $poem = $stmt->fetch(PDO::FETCH_ASSOC);
    
            return $poem;
        } catch (PDOException $e) {
            throw new Exception("Erro ao obter o poema: " . $e->getMessage());
        }
    }

    public static function getAll() {
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

    public static function getByCategory($categoryId) {
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

    public static function getByUser($userId) {
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

    public static function getCategories() {
        $database = new Database();
        $conn = $database->getConnection();

        $sql = "SELECT * FROM categories";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function search($keyword) {
        $database = new Database();
        $conn = $database->getConnection();

        $sql = "SELECT * FROM poems WHERE title LIKE :keyword OR content LIKE :keyword";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':keyword', '%' . $keyword . '%');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para excluir um poema
    public function deletePoem($poemId, $authorId) {
        $database = new Database();
        $conn = $database->getConnection();

        // Verifica se o poema existe e se o usuário é o autor
        $checkSql = "SELECT id FROM poems WHERE id = :poem_id AND author_id = :author_id";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bindValue(':poem_id', $poemId);
        $checkStmt->bindValue(':author_id', $authorId);
        $checkStmt->execute();

        if ($checkStmt->rowCount() === 0) {
            return "O poema não existe ou você não tem permissão para excluí-lo.";
        }

        // Inicia a transação
        $conn->beginTransaction();

        try {
            // Exclui o poema (isso vai automaticamente excluir as tags associadas devido ao ON DELETE CASCADE)
            $deleteSql = "DELETE FROM poems WHERE id = :poem_id";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bindValue(':poem_id', $poemId);
            $deleteStmt->execute();

            // Confirma a transação
            $conn->commit();
            return "Poema excluído com sucesso.";
        } catch (Exception $e) {
            // Desfaz a transação em caso de erro
            $conn->rollBack();
            return "Erro ao excluir o poema: " . $e->getMessage();
        }
    }

    public static function getAllPoemsWithTagsAndProfilePictures() {
        $database = new Database();
        $conn = $database->getConnection();
        
        $sql = "
            SELECT poems.id, poems.title, poems.content, poems.visibility, poems.author_id, poems.category_id, 
                   categories.name AS category_name, users.username, profile.profile_picture,
                   GROUP_CONCAT(tags.name SEPARATOR ', ') AS tags
            FROM poems
            INNER JOIN categories ON poems.category_id = categories.id
            INNER JOIN users ON poems.author_id = users.id
            LEFT JOIN profile ON users.id = profile.user_id
            LEFT JOIN poem_tags ON poems.id = poem_tags.poem_id
            LEFT JOIN tags ON poem_tags.tag_id = tags.id
            WHERE poems.visibility = 'public' 
            GROUP BY poems.id
        ";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    
    
}
?>
