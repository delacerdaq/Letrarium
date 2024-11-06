<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in.']);
    exit();
}

require_once '../config/database.php'; 
require_once '../controller/LikeController.php';

$database = new Database();
$db = $database->getConnection();

$likeController = new LikeController($db);
$user_id = $_SESSION['user_id'];

// Verificar se estamos solicitando as curtidas existentes
if (isset($_GET['get_likes']) && $_GET['get_likes'] === 'true') {
    $likedPoems = $likeController->getAllLikedPoems($user_id);
    echo json_encode(['liked_poems' => $likedPoems]);
    exit();
}

// Verifica se os parâmetros para curtida foram enviados
if (isset($_POST['liked']) && isset($_POST['poem_id'])) {
    $poem_id = $_POST['poem_id'];
    $liked = $_POST['liked'] === 'true';

    // Alterna o estado da curtida
    if ($liked) {
        $result = $likeController->likePoem($user_id, $poem_id);
    } else {
        $result = $likeController->unlikePoem($user_id, $poem_id);
    }

    echo json_encode(['status' => $result ? 'success' : 'error']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Parâmetros ausentes.']);
}
?>
