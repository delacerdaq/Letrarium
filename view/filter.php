<?php
session_start();
require_once '../controller/PoemController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$poemController = new PoemController();
$poems = [];

$categoryFilter = isset($_GET['category_id']) ? $_GET['category_id'] : null;

$poems = $categoryFilter ? $poemController->getPoemsByCategory($categoryFilter) : $poemController->getAllPoems();
$categories = $poemController->getCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Poems</title>
</head>
<body class="bg-[#fef7ed] min-h-screen text-gray-800">
    <script src="https://cdn.tailwindcss.com"></script>

    <header class="bg-white shadow-md py-4 px-6 flex items-center justify-between">
        <nav class="flex items-center gap-6">
            <div class="space-x-4">
                <a href="#" class="text-purple-700 font-semibold hover:underline">Desafios</a>
                <a href="publish_poem.php" class="text-purple-700 font-semibold hover:underline">Criar</a>
            </div>
        </nav>

        <form method="GET" action="filter.php" class="flex items-center gap-3">
            <label for="category" class="text-sm text-purple-700 font-semibold">Categoria:</label>
            <select name="category_id" id="category"
                class="rounded-lg border border-purple-300 px-3 py-1 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="">Todas as Categorias</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id']; ?>" <?= ($categoryFilter == $category['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit"
                class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 font-semibold transition">
                Filtrar
            </button>
        </form>

        <a href="user_profile.php" class="ml-6">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" class="fill-purple-700 hover:fill-purple-900 transition">
                <path d="M12 2C6.579 2 2 6.579 2 12s4.579 10 10 10 10-4.579 10-10S17.421 2 12 2zm0 5c1.727 0 3 1.272 3 3s-1.273 3-3 3c-1.726 0-3-1.272-3-3s1.274-3 3-3zm-5.106 9.772c.897-1.32 2.393-2.2 4.106-2.2h2c1.714 0 3.209.88 4.106 2.2C15.828 18.14 14.015 19 12 19s-3.828-.86-5.106-2.228z"/>
            </svg>
        </a>
    </header>

    <div class="text-center mt-6">
        <p class="text-xl font-medium text-purple-700">Explore novos tipos de escrita e criatividade.</p>
        <a href="user_dashboard.php"
           class="inline-block mt-2 text-sm text-white bg-purple-600 px-4 py-2 rounded-lg hover:bg-purple-700 transition font-semibold">
            Voltar ao Dashboard
        </a>
    </div>

    <div class="max-w-4xl mx-auto mt-10 px-4">
        <?php if (!empty($poems)): ?>
            <ul class="grid gap-6">
                <?php foreach ($poems as $poem): ?>
                    <li class="bg-white p-6 rounded-xl shadow-md">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center overflow-hidden">
                                <?php if (!empty($poem['profile_picture']) && file_exists($poem['profile_picture'])): ?>
                                    <img src="<?= htmlspecialchars($poem['profile_picture']); ?>" alt="Foto de perfil" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" class="fill-purple-400">
                                        <path d="M12 2C6.579 2 2 6.579 2 12s4.579 10 10 10 10-4.579 10-10S17.421 2 12 2zm0 5c1.727 0 3 1.272 3 3s-1.273 3-3 3c-1.726 0-3-1.272-3-3s1.274-3 3-3zm-5.106 9.772c.897-1.32 2.393-2.2 4.106-2.2h2c1.714 0 3.209.88 4.106 2.2C15.828 18.14 14.015 19 12 19s-3.828-.86-5.106-2.228z"/>
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <div class="text-purple-700 font-semibold">
                                <?= htmlspecialchars($poem['username']); ?>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold text-purple-900 text-center mb-2"><?= htmlspecialchars($poem['title']); ?></h3>
                        <p class="text-center text-gray-700 whitespace-pre-line"><?= nl2br(htmlspecialchars($poem['content'])); ?></p>
                        <small class="block mt-4 text-center text-sm text-purple-600">
                            Categoria: <?= htmlspecialchars($poem['category_name']); ?>
                        </small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-center text-gray-600 mt-10 text-lg font-medium">Nenhum poema encontrado para a categoria selecionada.</p>
        <?php endif; ?>
    </div>
</body>
</html>
