<?php
session_start();
require_once '../controller/PoemController.php';
require_once '../controller/LoadingController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$loadingController = LoadingController::getInstance();
$loadingController->startLoading();

$poemController = new PoemController();
$tag = isset($_GET['tag']) ? trim($_GET['tag']) : '';

if (empty($tag)) {
    echo "Nenhuma tag fornecida.";
    exit();
}
$poems = $poemController->getPoemsByTag($tag);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poemas com a Tag "<?php echo htmlspecialchars($tag); ?>"</title>
</head>

<body class="bg-[#fffbea] min-h-screen text-gray-800 font-sans">

<div id="header" class="bg-purple-800 text-white shadow-md">
    <nav class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
        <div class="links flex space-x-6">
            <a href="#" class="hover:text-purple-200 transition">desafios</a>
            <a href="publish_poem.php" class="hover:text-purple-200 transition">criar</a>
        </div>
        <div id="link-profile">
            <a href="user_profile.php" class="hover:opacity-80 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" class="fill-white">
                    <path d="M12 2C6.579 2 2 6.579 2 12s4.579 10 10 10 10-4.579 10-10S17.421 2 12 2zm0 5c1.727 0 3 1.272 3 3s-1.273 3-3 3c-1.726 0-3-1.272-3-3s1.274-3 3-3zm-5.106 9.772c.897-1.32 2.393-2.2 4.106-2.2h2c1.714 0 3.209.88 4.106 2.2C15.828 18.14 14.015 19 12 19s-3.828-.86-5.106-2.228z"></path>
                </svg>
            </a>
        </div>
    </nav>
</div>

<div id="welcome" class="text-center py-10 px-4">
    <p class="text-xl font-medium text-purple-700 mb-4">Encontre aqui os poemas desejados com as respectivas tags!</p>
    <a href="user_dashboard.php" class="inline-block bg-purple-700 text-white px-6 py-2 rounded-lg hover:bg-purple-800 transition shadow-md">
        Voltar ao dashboard
    </a>
</div>

<div id="poems-section" class="max-w-4xl mx-auto bg-white rounded-2xl p-6 shadow-lg">
    <h2 class="text-2xl font-bold text-purple-800 mb-6">Poemas com a Tag "<?php echo htmlspecialchars($tag); ?>"</h2>

    <?php if (!empty($poems)): ?>
        <ul class="space-y-6">
        <?php foreach ($poems as $poem): ?>
            <li class="bg-[#f7f1ea] rounded-lg p-4 shadow-md">
                <h3 class="text-xl font-semibold text-purple-700"><?php echo htmlspecialchars($poem['title']); ?></h3>
                <p class="mt-2 text-gray-700 whitespace-pre-line"><?php echo nl2br(htmlspecialchars($poem['content'])); ?></p>
                <div class="mt-4 text-sm text-gray-600 flex flex-col sm:flex-row sm:justify-between">
                    <span>Autor: <strong><?php echo htmlspecialchars($poem['username']); ?></strong></span>
                    <span>Categoria: <strong><?php echo htmlspecialchars($poem['category_name']); ?></strong></span>
                </div>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-center text-red-600 font-medium mt-6">Nenhum poema encontrado para esta tag.</p>
    <?php endif; ?>
</div>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../js/loading.js"></script>
</body>
</html>
