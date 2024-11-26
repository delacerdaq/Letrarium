<?php
require_once 'database.php';
require_once '../model/tag.php';

interface ITagDao{
    public function addTag($poemId, $tag);
    public function getTags($poemId);
    public function editTags($poemId, $tags);
}

class TagDao implements ITagDao{

    private $conn;
    private $table = 'tags';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Método para adicionar uma tag ao poema, usando composição
    public function addTag($poemId, $tag) {
        // Verifica se a tag já existe
        $stmt = $this->conn->prepare("SELECT id FROM tags WHERE name = :name");
        $stmt->bindValue(':name', $tag);
        $stmt->execute();
        $tagId = $stmt->fetchColumn();

        // Se a tag não existe, cria uma nova
        if (!$tagId) {
            $stmt = $this->conn->prepare("INSERT INTO tags (name) VALUES (:name)");
            $stmt->bindValue(':name', $tag);
            $stmt->execute();
            $tagId = $this->conn->lastInsertId();
        }

        // Associa a tag ao poema
        $stmt = $this->conn->prepare("INSERT IGNORE INTO poem_tags (poem_id, tag_id) VALUES (:poemId, :tagId)");
        $stmt->bindValue(':poemId', $poemId);
        $stmt->bindValue(':tagId', $tagId);
        return $stmt->execute();
    }

    // Método para recuperar tags associadas a um poema, usando agregação
    public function getTags($poemId) {
        $stmt = $this->conn->prepare("SELECT t.name FROM tags t 
                                    INNER JOIN poem_tags pt ON t.id = pt.tag_id 
                                    WHERE pt.poem_id = :poemId");
        $stmt->bindValue(':poemId', $poemId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para atualizar tags associadas a um poema
    public function editTags($poemId, $tags) {
        // Remove todas as tags existentes para o poema
        $deleteSql = "DELETE FROM poem_tags WHERE poem_id = :poemId";
        $deleteStmt = $this->conn->prepare($deleteSql);
        $deleteStmt->bindValue(':poemId', $poemId);
        $deleteStmt->execute();

        // Adiciona as novas tags
        $insertSql = "INSERT INTO poem_tags (poem_id, tag_id) VALUES (:poemId, :tagId)";
        $insertStmt = $this->conn->prepare($insertSql);

        foreach ($tags as $tagName) {
            // Verifica se a tag já existe
            $tagSql = "SELECT id FROM tags WHERE name = :tagName";
            $tagStmt = $this->conn->prepare($tagSql);
            $tagStmt->bindValue(':tagName', $tagName);
            $tagStmt->execute();

            $tagId = $tagStmt->fetchColumn();

            // Se a tag não existir, cria uma nova
            if (!$tagId) {
                $insertTagSql = "INSERT INTO tags (name) VALUES (:tagName)";
                $insertTagStmt = $this->conn->prepare($insertTagSql);
                $insertTagStmt->bindValue(':tagName', $tagName);
                $insertTagStmt->execute();
                $tagId = $this->conn->lastInsertId();
            }

            // Insere a relação do poema com a tag
            $insertStmt->bindValue(':poemId', $poemId);
            $insertStmt->bindValue(':tagId', $tagId);
            $insertStmt->execute();
        }

        return true;
    }
}
?>