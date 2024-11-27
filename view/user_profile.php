<?php
session_start();
require_once '../controller/poemController.php';
require_once '../controller/ProfileController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$profileController = new ProfileController();
$profile = $profileController->getProfileByUserId($user_id);

// Verifica se o perfil foi encontrado e se há uma foto de perfil
$profilePicture = (!empty($poem['profile_picture']) && file_exists($poem['profile_picture']));
$bio = !empty($profile['bio']) ? $profile['bio'] : '';

// Obtém os poemas publicados pelo usuário
$poemController = new PoemController();
$poems = $poemController->getPoemsByUser($user_id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../css/user_profile.css">
</head>
<body>


<div id="profile-top">

    <div id="profile-picture">
    <?php if ($profilePicture): ?>
        <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture">
    <?php else: ?>
            <div class="placeholder">
                <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24">
                    <path d="M12 2C6.579 2 2 6.579 2 12s4.579 10 10 10 10-4.579 10-10S17.421 2 12 2zm0 5c1.727 0 3 1.272 3 3s-1.273 3-3 3c-1.726 0-3-1.272-3-3s1.274-3 3-3zm-5.106 9.772c.897-1.32 2.393-2.2 4.106-2.2h2c1.714 0 3.209.88 4.106 2.2C15.828 18.14 14.015 19 12 19s-3.828-.86-5.106-2.228z"></path>
                </svg>
            </div>
        <?php endif; ?>
        <a href="edit_profile.php" id="edit-picture">✎</a>
    </div>

    <h1><?php echo htmlspecialchars($username); ?></h1>
    <p><?php echo htmlspecialchars($bio); ?></p> <!-- Exibindo a bio -->
</div>

<div id="profile-options">
    <a href="edit_profile.php">Editar Perfil</a>
    <a href="reset_password.php">Mudar Senha</a>
    <a href="change_email.php">Mudar Email</a>
    <a href="settings.php">Configurações</a>
    <a href="preferences.php">Preferências</a>
    <a href="../view/user_dashboard.php">Voltar ao Dashboard</a>
    <a href="../view/logout.php">Logout</a>
</div>

<div id="poems-section">
    <h2>Meus Poemas</h2>
    <div class="poems-container">
        <?php if (!empty($poems)): ?>
            <?php foreach ($poems as $poem): ?>
                <div class="poem">
                    <h3><?php echo htmlspecialchars($poem['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($poem['content'])); ?></p>
                    <small>Categoria: <?php echo htmlspecialchars($poem['category_name']); ?></small>
                    <br>
                    <a href="edit_poem.php?id=<?php echo $poem['id']; ?>">Editar</a> |
                    <a href="delete_poem.php?id=<?php echo $poem['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este poema?');">Excluir</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Você ainda não publicou nenhum poema.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
