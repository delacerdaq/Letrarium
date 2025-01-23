<?php
session_start();
require_once '../controller/likeController.php';

header('Content-Type: application/json');  // Garante que o retorno seja JSON

// Verifica se o poema e o usuário estão definidos
if (isset($_POST['poem_id']) && isset($_SESSION['user_id'])) {
    $poemId = $_POST['poem_id'];
    $userId = $_SESSION['user_id'];

    $likeController = new LikeController($db);  // Certifique-se de usar o nome correto da classe LikeController

    // Verifica se o usuário já curtiu o poema
    $currentLike = $likeController->hasLiked($poemId, $userId);

    // Se já curtiu, descurte, caso contrário, curte
    if ($currentLike) {
        $likeController->unlikePoem($userId, $poemId);  // Corrigido: $userId e $poemId
        $liked = false;
    } else {
        $likeController->likePoem($userId, $poemId);  // Corrigido: $userId e $poemId
        $liked = true;
    }

    // Retorna a resposta como JSON
    echo json_encode([
        'success' => true,
        'liked' => $liked
    ]);
} else {
    // Retorna erro se os parâmetros não forem encontrados
    echo json_encode([
        'success' => false,
        'message' => 'Parâmetros inválidos.'
    ]);
}
?>
