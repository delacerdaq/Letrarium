<?php
require_once '../controller/ChallengeController.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$challengeController = new ChallengeController();

$successMessage = '';
$errorMessage = '';
$user_id = $_SESSION['user_id'];
$challenge_id = $_POST['challenge_id'] ?? ($_GET['challenge_id'] ?? null);

if (!$challenge_id || !is_numeric($challenge_id)) {
    die("challenge_id não está definido ou é inválido.");
}

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    $result = $challengeController->submitPoem($challenge_id, $userId, $title, $content);

    // Avaliar o resultado retornado pelo método submitPoem
    if ($result === "Poema submetido com sucesso.") {
        $successMessage = $result; // Mensagem de sucesso
    } else {
        $errorMessage = $result; // Mensagem de erro específica
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Poema</title>
    <link rel="stylesheet" href="../css/publish_challenge_poem.css">
</head>
<body>
    <div class="form-container">
        <form action="publish_challenge_poem.php" method="post">
            <h1>Publicar Poema</h1>

            <?php if ($successMessage): ?>
                    <p style="text-align: center; color: green;"><?= $successMessage ?></p>
                <?php endif; ?>
                <?php if ($errorMessage): ?>
                    <p style="text-align: center; color: red;"><?= $errorMessage ?></p>
                <?php endif; ?>

            <input type="hidden" name="challenge_id" value="<?= htmlspecialchars($challenge_id) ?>"> 

            <label for="title">Título</label>
            <input type="text" id="title" name="title" placeholder="Digite o título do poema" required>

            <label for="content">Conteúdo</label>
            <textarea id="content" name="content" placeholder="Escreva o poema aqui" rows="8" required></textarea>

            <div class="button-group">
                <button type="button" class="back-button" onclick="history.back()">Voltar</button>
                <button type="submit" class="publish-button">Publicar</button>
            </div>
        </form>
    </div>
</body>
</html>