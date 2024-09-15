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
    // Carrega o poema para confirmação de exclusão
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
    <link rel="stylesheet" href="../css/delete_poem.css">
    <script src="../js/poem.js" defer></script>
</head>
<body>

<div class="container">
    <form id="delete-form" method="post" action="">
    <h1>Excluir Poema</h1>
    
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($poemId); ?>">
        <p>Você tem certeza de que deseja excluir o poema "<strong><?php echo htmlspecialchars($poem['title']); ?></strong>"?</p>
        <button type="submit">Excluir</button>
        <a href="user_profile.php">Cancelar</a>
    </form>
    <?php if (isset($error)): ?>
        <p><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
</div>

</body>
</html>
