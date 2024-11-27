<?php
session_start();
require_once '../controller/PoemController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$poemController = new PoemController();
$message = '';

// Obtém o ID do poema a ser editado via GET
$poemId = isset($_GET['id']) ? $_GET['id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'],
        'content' => $_POST['content'],
        'category_id' => $_POST['category_id'],
        'visibility' => $_POST['visibility'],
        'tags' => isset($_POST['tags']) ? explode(',', $_POST['tags']) : []
    ];

    // Edita o poema através do controlador
    $resultMessage = $poemController->editPoem($poemId, $data);

    // Verifica se a mensagem de sucesso ou erro
    if (strpos($resultMessage, 'sucesso') !== false) {
        $message = "<p style='color: green;'>$resultMessage</p>"; // Mensagem de sucesso em verde
    } else {
        $message = "<p style='color: red;'>$resultMessage</p>"; // Mensagem de erro em vermelho
    }
}

// Busca o poema atual e categorias para exibir no formulário
$poemData = $poemController->getPoemById($poemId);
$categories = $poemController->getCategories();

// Garante que $poemData['tags'] seja um array
$tags = isset($poemData['tags']) ? $poemData['tags'] : [];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Poema</title>
    <link rel="stylesheet" href="../css/edit_poem.css">
</head>
<body>

    <?php if ($poemData) : ?>
        <form action="edit_poem.php?id=<?php echo htmlspecialchars($poemId); ?>" method="POST">
        <h1>Editar Poema</h1>

        <!-- Exibe a mensagem de sucesso ou erro -->
        <?php echo $message; ?>

            <label for="title">Título:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($poemData['title']); ?>" required>
            <br>

            <label for="content">Conteúdo:</label>
            <textarea name="content" required><?php echo htmlspecialchars($poemData['content']); ?></textarea>
            <br>

            <label for="category_id">Categoria:</label>
            <select name="category_id" required>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo htmlspecialchars($category['id']); ?>" <?php echo ($poemData['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br>

            <label for="visibility">Visibilidade:</label>
            <select name="visibility" required>
                <option value="public" <?php echo ($poemData['visibility'] == 'public') ? 'selected' : ''; ?>>Público</option>
                <option value="restricted" <?php echo ($poemData['visibility'] == 'restricted') ? 'selected' : ''; ?>>Restrito</option>
            </select>
            <br>

            <label for="tags">Tags:</label>
            <input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars(implode(', ', $poemData['tags'])); ?>">
            <br>

            <button type="submit">Salvar</button>

            <a href="user_dashboard.php">Voltar ao Dashboard</a>
        </form>
    <?php else : ?>
        <p>Poema não encontrado.</p>
    <?php endif; ?>
</body>
</html>
