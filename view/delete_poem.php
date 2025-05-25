<?php
session_start();
require_once '../controller/PoemController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$poemController = new PoemController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $poemId = $_POST['id'];
    if ($poemController->deletePoem($poemId, $user_id)) {
        header("Location: user_profile.php?message=Poema excluído com sucesso!");
        exit();
    } else {
        $error = "Erro ao excluir o poema.";
    }
} else {
    $poemId = $_GET['id'];
    $poem = $poemController->getPoemById($poemId);

    if (!$poem) {
        die("Poema não encontrado.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Poema</title>
    <script src="../js/poem.js" defer></script>
</head>
<body class="bg-[#fffbea] min-h-screen flex items-center justify-center py-10 px-4 sm:px-6">
<script src="https://cdn.tailwindcss.com"></script>

<div class="w-full max-w-xl bg-white rounded-2xl shadow-lg p-8">
    <form id="delete-form" method="post" action="" class="space-y-6">
        <h1 class="text-3xl font-bold text-purple-700 text-center">Excluir Poema</h1>

        <input type="hidden" name="id" value="<?php echo htmlspecialchars($poemId); ?>">

        <p class="text-gray-700 text-lg text-center">
            Você tem certeza de que deseja excluir o poema
            <strong class="text-purple-800">"<?php echo htmlspecialchars($poem['title']); ?>"</strong>?
        </p>

        <div class="flex flex-col sm:flex-row justify-center items-center gap-4 pt-4">
            <button type="submit"
                    class="bg-red-600 text-white font-semibold px-6 py-2 rounded-lg shadow-md hover:bg-red-700 transition">
                Excluir
            </button>

            <a href="user_profile.php"
               class="text-purple-700 font-medium underline hover:text-purple-900 transition">
                Cancelar
            </a>
        </div>
    </form>

    <?php if (isset($error)): ?>
        <p class="mt-4 text-red-600 text-center"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
</div>

<script src="https://cdn.tailwindcss.com"></script>
</body>
</html>
