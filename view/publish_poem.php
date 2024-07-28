<?php
session_start();
require_once '../model/Poem.php';
require_once '../controller/poemController.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$poemController = new PoemController();
// Recupera categorias do banco de dados
$categories = $poemController->getCategories();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $visibility = $_POST['visibility'];
    $categoryId = $_POST['category'];
    $authorId = $_SESSION['user_id'];

    $poem = new Poem($title, $content, $visibility, $authorId, $categoryId);
    $validationResult = $poemController->validatePoem($poem);

    if ($validationResult === true) {
        if ($poemController->savePoem($poem)) {
            $successMessage = "Poema publicado com sucesso!";
        } else {
            $errorMessage = "Erro ao salvar o poema. Por favor, tente novamente.";
        }
    } else {
        $errorMessage = $validationResult;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Poema</title>
    <link rel="stylesheet" href="../css/publish_poem.css">
</head>
<body>
    <div class="container">
        <h1>Publicar Poema</h1>

        <?php
        if (isset($successMessage)) {
            echo "<p style='color: green;'>$successMessage</p>";
        } elseif (isset($errorMessage)) {
            echo "<p style='color: red;'>$errorMessage</p>";
        }
        ?>

        <form action="publish_poem.php" method="POST">
            <div class="form-group">
                <label for="title">Título:</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="content">Conteúdo do Poema:</label>
                <textarea id="content" name="content" rows="10" required></textarea>
            </div>

            <div class="form-group">
                <label for="visibility">Visibilidade:</label>
                <select id="visibility" name="visibility" required>
                    <option value="public">Público</option>
                    <option value="restricted">Restrito</option>
                </select>
            </div>

            <div class="form-group">
                <label for="category">Categoria:</label>
                <select id="category" name="category" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['id']); ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn">Publicar</button>
        </form>

        <a href="user_dashboard.php">Voltar ao Dashboard</a>
    </div>
</body>
</html>
