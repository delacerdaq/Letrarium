<?php
require_once 'database.php';
require_once '../model/challenge.php';

interface IChallengeDao
{
    public function fetchAllChallenges();
    public function getAllChallengesWithProfiles($challengeId);
    public function createChallenge($theme, $description, $monthYear, $adminId);
    public function submitPoem($challengeId, $userId, $title, $content);
    public function votePoem($poemId, $userId);
    public function unvotePoem($poemId, $userId);
    public function checkVote($poemId, $userId);
    public function determineWinner($challengeId);
    public function editChallenge($challengeId, $theme, $description, $monthYear, $adminId);
    public function deleteChallenge($challengeId, $adminId);
}

class ChallengeDAO implements IChallengeDao
{

    private $conn;
    private $table = 'challenges';

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function fetchAllChallenges()
    {
        $sql = "SELECT * FROM challenges";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getChallengePoemById($id)
    {
        $sql = "SELECT * FROM challenge_poems WHERE id = :id and challenge_id = :challenge_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Liga o parâmetro $id à consulta
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result; // Retorna o poema como um array associativo
        }

        return null; // Retorna null se nenhum poema for encontrado
    }

    public function getAllChallengesWithProfiles($challengeId)
    {
        $sql = "
        SELECT u.name AS user_name,
        p.profile_picture AS profile_picture,
        cp.title AS poem_title,
        cp.content AS poem_content
        FROM challenge_poems cp
        INNER JOIN users u ON cp.user_id = u.id
        LEFT JOIN profile p ON u.id = p.user_id
        WHERE cp.challenge_id = :challenge_id";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':challenge_id', $challengeId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar desafios com perfis: " . $e->getMessage());
            return [];
        }
    }

    public function createChallenge($theme, $description, $monthYear, $adminId)
    {
        if (!$this->isAdmin($adminId)) {
            throw new Exception("Apenas administradores podem criar os desafios.");
        }

        $sql = "INSERT INTO challenges (theme, description, month_year, created_at) VALUES (:theme, :description, :month_year, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':theme', $theme);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':month_year', $monthYear);
        $stmt->execute();
    }

    public function submitPoem($challengeId, $userId, $title, $content)
    {
        // Verificar se o usuário já enviou um poema para este desafio
        $checkSql = "SELECT COUNT(*) AS count FROM challenge_poems WHERE challenge_id = :challenge_id AND user_id = :user_id";
        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->bindValue(':challenge_id', $challengeId);
        $checkStmt->bindValue(':user_id', $userId);
        $checkStmt->execute();

        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            throw new Exception("Você já enviou um poema para este desafio.");
        }

        // Inserir o novo poema
        $sql = "INSERT INTO challenge_poems (challenge_id, user_id, title, content, created_at) 
                VALUES (:challenge_id, :user_id, :title, :content, CURRENT_TIMESTAMP)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':challenge_id', $challengeId);
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':content', $content);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function votePoem($poemId, $userId)
    {
        $sql = "INSERT INTO challenge_votes (challenge_poem_id, user_id) VALUES (:challenge_poem_id, :user_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':challenge_poem_id', $poemId);
        $stmt->bindValue(':user_id', $userId);
        if (!$stmt->execute()) {
            throw new Exception("Erro ao votar no poema: " . $stmt->errorInfo()[2]);
        }
    }

    public function unvotePoem($poemId, $user_id)
    {
        $sql = "DELETE FROM challenge_votes WHERE challenge_poem_id = :challenge_poem_id AND user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':challenge_poem_id', $poemId, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        if (!$stmt->execute()) {
            throw new Exception("Erro ao remover o voto do poema: " . $stmt->errorInfo()[2]);
        }
    }

    public function checkVote($poemId, $user_id)
    {
        $sql = "SELECT COUNT(*) FROM challenge_votes WHERE challenge_poem_id = :challenge_poem_id AND user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':challenge_poem_id', $poemId, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $voteCount = $stmt->fetchColumn();

        return $voteCount > 0 ? 1 : 0;  // Retorna 1 se o usuário já votou, 0 caso contrário
    }

    public function determineWinner($challengeId)
    {
        // Consulta para contar votos por usuário em poemas do desafio
        $sql = "
            SELECT cp.user_id, COUNT(cv.id) AS vote_count 
            FROM challenge_poems cp
            LEFT JOIN challenge_votes cv 
            ON cp.id = cv.challenge_poem_id
            WHERE cp.challenge_id = :challenge_id 
            GROUP BY cp.user_id 
            ORDER BY vote_count DESC 
            LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':challenge_id', $challengeId, PDO::PARAM_INT);
        $stmt->execute();

        $winner = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se houver um vencedor, atualize a tabela de desafios com o ID do usuário vencedor
        if ($winner) {
            $sqlUpdate = "
                UPDATE challenges 
                SET winner_user_id = :winner_user_id 
                WHERE id = :challenge_id";
            $stmtUpdate = $this->conn->prepare($sqlUpdate);
            $stmtUpdate->bindValue(':winner_user_id', $winner['user_id'], PDO::PARAM_INT);
            $stmtUpdate->bindValue(':challenge_id', $challengeId, PDO::PARAM_INT);
            $stmtUpdate->execute();

            // Atualizar a tabela de perfil para adicionar o desafio aos desafios vencidos
            $sqlProfileUpdate = "
                UPDATE profile 
                SET winner_challenges = CONCAT(COALESCE(winner_challenges, ''), :challenge_id, ',') 
                WHERE user_id = :user_id";
            $stmtProfile = $this->conn->prepare($sqlProfileUpdate);
            $stmtProfile->bindValue(':challenge_id', $challengeId, PDO::PARAM_STR); // Salva como string separada por vírgulas
            $stmtProfile->bindValue(':user_id', $winner['user_id'], PDO::PARAM_INT);
            $stmtProfile->execute();
        }
    }

    public function editChallenge($challengeId, $theme, $description, $monthYear, $adminId)
    {
        if (!$this->isAdmin($adminId)) {
            throw new Exception("Only admins can edit challenges.");
        }

        $sql = "UPDATE challenges SET theme = :theme, description = :description, month_year = :month_year WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':theme', $theme);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':month_year', $monthYear);
        $stmt->bindValue(':id', $challengeId);
        $stmt->execute();
    }

    public function deleteChallenge($challengeId, $adminId)
    {
        if (!$this->isAdmin($adminId)) {
            throw new Exception("Only admins can delete challenges.");
        }

        $sql = "DELETE FROM challenges WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $challengeId);
        $stmt->execute();
    }

    private function isAdmin($userId)
    {
        $sql = "SELECT is_admin FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $userId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result['is_admin'] == 1;
    }
}
?>