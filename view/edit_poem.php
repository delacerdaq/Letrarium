<?php
session_start();
require_once '../controller/PoemController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$poemController = new PoemController();
$message = '';
$poemId = isset($_GET['id']) ? $_GET['id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'],
        'content' => $_POST['content'],
        'category_id' => $_POST['category_id'],
        'visibility' => $_POST['visibility'],
        'tags' => isset($_POST['tags']) ? explode(',', $_POST['tags']) : []
    ];

    $resultMessage = $poemController->editPoem($poemId, $data);

    if (strpos($resultMessage, 'sucesso') !== false) {
        $message = "<p style='color: green;'>$resultMessage</p>";
    } else {
        $message = "<p style='color: red;'>$resultMessage</p>";
    }
}
$poemData = $poemController->getPoemById($poemId);
$categories = $poemController->getCategories();

$tags = isset($poemData['tags']) ? $poemData['tags'] : [];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Poema</title>
</head>
<body class="bg-[#fffbea] min-h-screen py-10 px-4 sm:px-6">

    <?php if ($poemData) : ?>
        <form action="edit_poem.php?id=<?php echo htmlspecialchars($poemId); ?>" method="POST"
              class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg p-8 space-y-6">

            <h1 class="text-3xl font-bold text-purple-700 text-center mb-6">Editar Poema</h1>

            <?php if (!empty($message)): ?>
                <div class="bg-purple-100 border border-purple-300 text-purple-800 px-4 py-2 rounded">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div>
                <label for="title" class="block text-purple-800 font-medium mb-1">Título:</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($poemData['title']); ?>" required
                       class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-400">
            </div>

            <div>
                <label for="content" class="block text-purple-800 font-medium mb-1">Conteúdo:</label>
                <textarea name="content" required rows="6"
                          class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-400"><?php echo htmlspecialchars($poemData['content']); ?></textarea>
            </div>

            <div>
                <label for="category_id" class="block text-purple-800 font-medium mb-1">Categoria:</label>
                <select name="category_id" required
                        class="w-full px-4 py-2 rounded border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-purple-400">
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo htmlspecialchars($category['id']); ?>"
                            <?php echo ($poemData['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="visibility" class="block text-purple-800 font-medium mb-1">Visibilidade:</label>
                <select name="visibility" required
                        class="w-full px-4 py-2 rounded border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-purple-400">
                    <option value="public" <?php echo ($poemData['visibility'] == 'public') ? 'selected' : ''; ?>>Público</option>
                    <option value="restricted" <?php echo ($poemData['visibility'] == 'restricted') ? 'selected' : ''; ?>>Restrito</option>
                </select>
            </div>

            <div>
                <label for="tags" class="block text-purple-800 font-medium mb-1">Tags:</label>
                <input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars(implode(', ', $poemData['tags'])); ?>"
                       class="w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-400">
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                <button type="submit"
                        class="bg-purple-600 text-white font-semibold px-6 py-2 rounded-lg shadow-md hover:bg-purple-700 transition">
                    Salvar
                </button>

                <a href="user_dashboard.php"
                   class="text-purple-700 font-medium underline hover:text-purple-900 transition">
                    Voltar ao Dashboard
                </a>
            </div>
        </form>
    <?php else : ?>
        <div class="text-center text-gray-600 text-lg mt-10">
            <p>Poema não encontrado.</p>
        </div>
    <?php endif; ?>

    <script src="https://cdn.tailwindcss.com"></script>
</body>
</html>
