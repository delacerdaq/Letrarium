<?php
require_once '../config/ChallengeDAO.php';
require_once '../config/UserDAO.php';

class ChallengeController
{

    private $challengeDAO;
    private $userDAO;

    public function __construct()
    {
        $this->challengeDAO = new ChallengeDAO();
        $this->userDAO = new UserDAO(); // Para verificar se o usuário é admin
    }

    public function isAdmin($userId)
    {
        $user = $this->userDAO->getUserById($userId);

        // Verifica se o campo is_admin existe e se é igual a 1 (admin)
        return isset($user['is_admin']) && $user['is_admin'] == 1;
    }

    public function createChallenge($title, $description, $startDate, $endDate, $adminId)
    {
        if (!$this->isAdmin($adminId)) {
            return "Apenas administradores podem criar desafios.";
        }

        $monthYear = date('F Y', strtotime($startDate));
        return $this->challengeDAO->createChallenge($title, $description, $monthYear, $adminId)
            ? "Desafio criado com sucesso."
            : "Erro ao criar o desafio.";
    }

    public function submitPoem($challengeId, $userId, $title, $content)
    {
        // Verificar se o poema está vazio
        if (empty($content)) {
            return "O poema não pode estar vazio.";
        }

        try {
            // Tentar submeter o poema
            $this->challengeDAO->submitPoem($challengeId, $userId, $title, $content);
            return "Poema submetido com sucesso.";
        } catch (Exception $e) {
            // Capturar exceção e retornar a mensagem de erro
            return $e->getMessage();
        }
    }

    public function getAllChallengesWithProfiles($challengeId)
    {
        try {
            return $this->challengeDAO->getAllChallengesWithProfiles($challengeId);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getChallengePoemById($id)
    {
        try {
            return $this->challengeDAO->getChallengePoemById($id);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function checkVoteStatus($poemId, $user_id)
    {
        try {
            // Verifica se já existe um voto do usuário nesse poema
            $voteStatus = $this->challengeDAO->checkVote($poemId, $user_id);
            return $voteStatus; // Retorna 1 se o usuário já votou, 0 caso contrário
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function voteOnPoem($userId, $poemId)
    {
        try {
            // Verifica se o usuário já votou
            $voteStatus = $this->checkVoteStatus($poemId, $userId);
            if ($voteStatus) {
                return 'Você já votou neste poema!';
            }
            $this->challengeDAO->votePoem($poemId, $userId);
            return 'Voto submetido com sucesso.';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function unvotePoem($poemId, $user_id)
    {
        try {
            // Verifica se o usuário já votou
            $voteStatus = $this->checkVoteStatus($poemId, $user_id);
            if (!$voteStatus) {
                return 'Você ainda não votou neste poema!';
            }
            $this->challengeDAO->unvotePoem($poemId, $user_id);
            return 'Voto excluído com sucesso.';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function handleVoteRequest() {
        $data = json_decode(file_get_contents("php://input"), true);
        $action = $data['action'] ?? null;
        $poemId = $data['poemId'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;
    
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
            return;
        }
    
        if (!$poemId) {
            echo json_encode(['success' => false, 'message' => 'ID do poema inválido.']);
            return;
        }
    
        if (!$action || !in_array($action, ['vote', 'unvote'])) {
            echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
            return;
        }
    
        try {
            if ($action === 'vote') {
                $result = $this->voteOnPoem($userId, $poemId);
            } elseif ($action === 'unvote') {
                $result = $this->unvotePoem($poemId, $userId);
            }
    
            echo json_encode(['success' => true, 'message' => $result]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }    

    public function determineWinner($challengeId)
    {
        try {
            $winner = $this->challengeDAO->determineWinner($challengeId);
            return $winner;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function editChallenge(
        $challengeId,
        $data,
        $adminId
    ) {
        if (!$this->isAdmin($adminId)) {
            return "Apenas administradores podem editar desafios.";
        }

        $title = $data['title'] ?? null;
        $description = $data['description'] ?? null;
        $startDate = $data['start_date'] ?? null;
        $endDate = $data['end_date'] ?? null;

        return $this->challengeDAO->editChallenge($challengeId, $title, $description, $startDate, $endDate)
            ? "Desafio editado com sucesso."
            : "Erro ao editar o desafio.";
    }

    public function deleteChallenge($challengeId, $adminId)
    {
        if (!$this->isAdmin($adminId)) {
            return "Apenas administradores podem excluir desafios.";
        }

        return $this->challengeDAO->deleteChallenge($challengeId, $adminId)
            ? "Desafio excluído com sucesso."
            : "Erro ao excluir o desafio.";
    }

    public function fetchAllChallenges()
    {
        return $this->challengeDAO->fetchAllChallenges();
    }
}
