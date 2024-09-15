<?php
session_start();
require_once '../model/Poem.php';
require_once '../controller/PoemController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$poemController = new PoemController();
$poems = [];

// Inicializa o filtro de categoria
$categoryFilter = isset($_GET['category_id']) ? $_GET['category_id'] : null;

// Busca poemas de acordo com o filtro
$poems = $categoryFilter ? $poemController->getPoemsByCategory($categoryFilter) : $poemController->getAllPoems();

$categories = $poemController->getCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Poems</title>
    <link rel="stylesheet" href="../css/filter.css">
</head>
<body>

    <div id="header">
        <nav>
            <div class="links">
                <a href="#">desafios</a>
                <a href="publish_poem.php">criar</a>
            </div>

            
        <form method="GET" action="filter.php">
            <label for="category">Filtrar por categoria:</label>
            <select name="category_id" id="category">
                <option value="">Todas as Categorias</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php if ($categoryFilter == $category['id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Filtrar</button>
        </form>


            <div id="link-profile">
                <a href="user_profile.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(231, 231, 231, 1);"><path d="M12 2C6.579 2 2 6.579 2 12s4.579 10 10 10 10-4.579 10-10S17.421 2 12 2zm0 5c1.727 0 3 1.272 3 3s-1.273 3-3 3c-1.726 0-3-1.272-3-3s1.274-3 3-3zm-5.106 9.772c.897-1.32 2.393-2.2 4.106-2.2h2c1.714 0 3.209.88 4.106 2.2C15.828 18.14 14.015 19 12 19s-3.828-.86-5.106-2.228z"></path></svg>
                </a>
            </div>
        </nav>
    </div>

    <div id="welcome">
        <p>Explore novos tipos de escrita e criatividade.</p>
    </div>

    <div id="poems-section">
        

        <?php if (!empty($poems)): ?>
            <ul>
                <?php foreach ($poems as $poem): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($poem['title']); ?></h3>
                        <p style="text-align: center;"><?php echo nl2br(htmlspecialchars($poem['content'])); ?></p>
                        <small>Categoria: <?php echo htmlspecialchars($poem['category_name']); ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p id="no-poem-result">Nenhum poema encontrado para a categoria selecionada.</p>
        <?php endif; ?>
    </div>

</body>
</html>
