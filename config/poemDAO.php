<?php
require_once 'database.php';
require_once '../model/poem.php';
require_once '../model/tag.php';

interface IPoemDao{
    public function save(Poem $poem);
    public function editPoem($poemId, $title, $content, $categoryId, $visibility);
    public function getById($poemId);
    public static function getAll();
    public static function getByCategory($categoryId);
    public static function getByUser($userId);
    public static function getCategories();
    public static function search($keyword);
    public function deletePoem($poemId, $authorId);
    public static function getAllPoemsWithTagsAndProfilePictures();
    public function fetchPoemsByTag($tag);
}

class PoemDAO implements IPoemDao{
    
    private $conn;
    private $tagDAO;
    private $table = 'poems';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->tagDAO = new tagDAO();
    }

    // CRUD Methods
    public function save(Poem $poem) {
        $sql = "INSERT INTO " . $this->table . "(title, content, visibility, author_id, category_id) VALUES (:title, :content, :visibility, :authorId, :categoryId)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':title', $poem->getTitle());
        $stmt->bindValue(':content', $poem->getContent());
        $stmt->bindValue(':visibility', $poem->getVisibility());
        $stmt->bindValue(':authorId', $poem->getAuthorId());
        $stmt->bindValue(':categoryId', $poem->getCategoryId());
        
        if ($stmt->execute()) {
            $poem->setId($this->conn->lastInsertId()); // Define o ID no objeto Poem
            return true;
        }
        return false;
    }
    

    public function editPoem($poemId, $title, $content, $categoryId, $visibility) {
        try {
        // Atualiza o poema
            $sql = "UPDATE " . $this->table .
                      " SET title = :title, content = :content, category_id = :categoryId, visibility = :visibility 
                      WHERE id = :poemId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
            $stmt->bindParam(':visibility', $visibility);
            $stmt->bindParam(':poemId', $poemId, PDO::PARAM_INT);
    
            $result = $stmt->execute();
    
            // Chama o método para atualizar as tags
            if ($result) {
                if (isset($tags) && is_array($tags)) {
                    return $this->tagDAO->editTags($poemId, $tags);
                }
            }
    
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Erro ao editar o poema: " . $e->getMessage());
        }
    }    

    public function getById($poemId) {
        try {
            $sql = "SELECT p.id, p.title, p.content, p.category_id, p.visibility, GROUP_CONCAT(t.name SEPARATOR ', ') as tags
                    FROM " . $this->table . " p
                    LEFT JOIN poem_tags pt ON p.id = pt.poem_id
                    LEFT JOIN tags t ON pt.tag_id = t.id
                    WHERE p.id = :poemId
                    GROUP BY p.id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':poemId', $poemId, PDO::PARAM_INT);
            $stmt->execute();
    
            $poem = $stmt->fetch(PDO::FETCH_ASSOC);
    
            return $poem;
        } catch (PDOException $e) {
            throw new Exception("Erro ao obter o poema: " . $e->getMessage());
        }
    }

    public static function getAll() {
        $sql = "SELECT poems.*, categories.name as category_name, users.username, profile.profile_picture
                FROM poems
                JOIN categories ON poems.category_id = categories.id
                JOIN users ON poems.author_id = users.id
                LEFT JOIN profile ON users.id = profile.user_id
                WHERE visibility = 'public'";
        $conn = (new Database())->getConnection(); 
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByCategory($categoryId) {
        $sql = "SELECT poems.*, categories.name as category_name FROM poems
                JOIN categories ON poems.category_id = categories.id
                WHERE category_id = :category_id AND visibility = 'public'";
        $conn = (new Database())->getConnection(); 
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByUser($userId) {
        $sql = "SELECT poems.*, categories.name as category_name FROM poems
                JOIN categories ON poems.category_id = categories.id
                WHERE author_id = :author_id";
        $conn = (new Database())->getConnection(); 
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':author_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCategories() {
        $sql = "SELECT * FROM categories";
        $conn = (new Database())->getConnection(); 
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function search($keyword) {
        $sql = "SELECT * FROM poems WHERE title LIKE :keyword OR content LIKE :keyword";
        $conn = (new Database())->getConnection(); 
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':keyword', '%' . $keyword . '%');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para excluir um poema
    public function deletePoem($poemId, $authorId) {
        // Verifica se o poema existe e se o usuário é o autor
        $checkSql = "SELECT id FROM " . $this->table . " WHERE id = :poem_id AND author_id = :author_id";
        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->bindValue(':poem_id', $poemId);
        $checkStmt->bindValue(':author_id', $authorId);
        $checkStmt->execute();

        if ($checkStmt->rowCount() === 0) {
            return "O poema não existe ou você não tem permissão para excluí-lo.";
        }

        // Inicia a transação
        $this->conn->beginTransaction();

        try {
            // Exclui o poema (isso vai automaticamente excluir as tags associadas devido ao ON DELETE CASCADE)
            $deleteSql = "DELETE FROM " . $this->table . " WHERE id = :poem_id";
            $deleteStmt = $this->conn->prepare($deleteSql);
            $deleteStmt->bindValue(':poem_id', $poemId);
            $deleteStmt->execute();

            // Confirma a transação
            $this->conn->commit();
            return "Poema excluído com sucesso.";
        } catch (Exception $e) {
            // Desfaz a transação em caso de erro
            $this->conn->rollBack();
            return "Erro ao excluir o poema: " . $e->getMessage();
        }
    }

    public static function getAllPoemsWithTagsAndProfilePictures() {
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
        
        // Utiliza a conexão já estabelecida no construtor
        $conn = (new Database())->getConnection(); 
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchPoemsByTag($tag) {
        $query = "SELECT p.id, p.title, p.content, p.category_id, c.name AS category_name, u.username
                  FROM poems p
                  JOIN categories c ON p.category_id = c.id
                  JOIN users u ON p.author_id = u.id
                  JOIN poem_tags pt ON p.id = pt.poem_id
                  JOIN tags t ON pt.tag_id = t.id
                  WHERE t.name = :tag";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tag', $tag, PDO::PARAM_STR);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}

?>