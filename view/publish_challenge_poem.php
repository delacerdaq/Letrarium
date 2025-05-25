<?php
require_once '../controller/ChallengeController.php';
require_once '../controller/LoadingController.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$loadingController = LoadingController::getInstance();
$loadingController->startLoading();

$challengeController = new ChallengeController();

$successMessage = '';
$errorMessage = '';
$user_id = $_SESSION['user_id'];
$challenge_id = $_POST['challenge_id'] ?? ($_GET['challenge_id'] ?? null);

if (!$challenge_id || !is_numeric($challenge_id)) {
    die("challenge_id não está definido ou é inválido.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    $result = $challengeController->submitPoem($challenge_id, $userId, $title, $content);

    if ($result === "Poema submetido com sucesso.") {
        $successMessage = $result;
    } else {
        $errorMessage = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Poema</title>
</head>
<body class="bg-[#fef7ed] min-h-screen flex items-center justify-center py-10 px-4">
    <div class="w-full max-w-2xl bg-white p-8 rounded-2xl shadow-lg">
        <form action="publish_challenge_poem.php" method="post" class="space-y-6">
            <h1 class="text-3xl font-bold text-purple-700 text-center">Publicar Poema</h1>

            <?php if ($successMessage): ?>
                <p class="text-green-600 text-center font-semibold"><?= $successMessage ?></p>
            <?php endif; ?>
            <?php if ($errorMessage): ?>
                <p class="text-red-600 text-center font-semibold"><?= $errorMessage ?></p>
            <?php endif; ?>

            <input type="hidden" name="challenge_id" value="<?= htmlspecialchars($challenge_id) ?>">

            <div>
                <label for="title" class="block text-sm font-medium text-purple-700">Título</label>
                <input type="text" id="title" name="title" placeholder="Digite o título do poema" required
                    class="mt-1 w-full px-4 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none shadow-sm">
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-purple-700">Conteúdo</label>
                <textarea id="content" name="content" rows="8" placeholder="Escreva o poema aqui" required
                    class="mt-1 w-full px-4 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none shadow-sm resize-y"></textarea>
            </div>

            <div class="flex justify-between">
                <button type="button" onclick="history.back()"
                    class="px-6 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition font-semibold">
                    Voltar
                </button>
                <button type="submit"
                    class="px-6 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition font-semibold shadow-md">
                    Publicar
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../js/loading.js"></script>
</body> 

</html>