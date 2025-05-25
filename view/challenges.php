<?php
require_once '../controller/ChallengeController.php';
require_once '../controller/LoadingController.php';

$loadingController = LoadingController::getInstance();
$loadingController->startLoading(); 

$challengeController = new ChallengeController();

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$isAdmin = $challengeController->isAdmin($user_id);
$challenges = $challengeController->fetchAllChallenges();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenges</title>
</head>
<body class="bg-[#fef7ed] min-h-screen py-10 px-4">
    <div class="max-w-7xl mx-auto bg-white p-8 rounded-2xl shadow-md">
        <h1 class="text-3xl font-bold text-purple-700 mb-6 text-center">Desafios</h1>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto text-sm text-left text-gray-700">
                <thead class="bg-purple-100 text-purple-700">
                    <tr>
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Tema</th>
                        <th class="px-4 py-3">Descrição</th>
                        <th class="px-4 py-3">Mês/Ano</th>
                        <th class="px-4 py-3">Postado em</th>
                        <th class="px-4 py-3 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-200">
                    <?php if (!empty($challenges)): ?>
                        <?php foreach ($challenges as $challenge): ?>
                            <tr class="hover:bg-purple-50 transition">
                                <td class="px-4 py-3"><?= htmlspecialchars($challenge['id']) ?></td>
                                <td class="px-4 py-3"><?= htmlspecialchars($challenge['theme']) ?></td>
                                <td class="px-4 py-3"><?= htmlspecialchars($challenge['description']) ?></td>
                                <td class="px-4 py-3"><?= htmlspecialchars($challenge['month_year']) ?></td>
                                <td class="px-4 py-3"><?= htmlspecialchars($challenge['created_at']) ?></td>
                                <td class="px-4 py-3 flex flex-wrap gap-2 justify-center items-center">
                                    <a href="publish_challenge_poem.php?challenge_id=<?= $challenge['id'] ?>"
                                       class="bg-purple-600 text-white px-3 py-1 rounded-lg hover:bg-purple-700 transition text-xs">
                                        Enviar Poema
                                    </a>
                                    <a href="view_challenge_poems.php?challenge_id=<?= $challenge['id'] ?>"
                                       class="bg-amber-400 text-white px-3 py-1 rounded-lg hover:bg-amber-500 transition text-xs">
                                        Ver Poemas
                                    </a>
                                    <?php if ($isAdmin): ?>
                                        <a href="edit_challenge.php?challenge_id=<?= $challenge['id'] ?>"
                                           class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 transition text-xs">
                                            Editar
                                        </a>
                                        <a href="delete_challenge.php?challenge_id=<?= $challenge['id'] ?>"
                                           class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition text-xs">
                                            Deletar
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">Nenhum desafio encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../js/loading.js"></script>
</body>
</html>
