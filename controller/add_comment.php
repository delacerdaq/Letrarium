<?php
session_start();
require_once '../controller/commentController.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

if (!isset($_POST['poem_id']) || !isset($_POST['content'])) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

$poemId = $_POST['poem_id'];
$userId = $_SESSION['user_id'];
$content = trim($_POST['content']);

if (empty($content)) {
    echo json_encode(['success' => false, 'message' => 'O comentário não pode estar vazio']);
    exit;
}

$commentController = new CommentController();
$success = $commentController->addComment($poemId, $userId, $content);

echo json_encode([
    'success' => $success,
    'message' => $success ? 'Comentário adicionado com sucesso' : 'Erro ao adicionar comentário'
]); 