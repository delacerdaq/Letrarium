<?php
session_start();
require_once '../controller/commentController.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

if (!isset($_GET['poem_id'])) {
    echo json_encode(['error' => 'ID do poema não fornecido']);
    exit;
}

$poemId = $_GET['poem_id'];
$commentController = new CommentController();
$comments = $commentController->getCommentsByPoemId($poemId);

echo json_encode($comments); 