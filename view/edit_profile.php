<?php
session_start();
require_once '../controller/ProfileController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$profileController = new ProfileController();
$profile = $profileController->getProfileByUserId($user_id);

// Verifica se a bio está definida
$bio = isset($profile['bio']) ? $profile['bio'] : '';

// Verifica se a foto de perfil está definida
$profilePicture = !empty($profile['profile_picture']) ? $profile['profile_picture'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newBio = $_POST['bio'] ?? '';

    // Processo de upload da nova foto de perfil, se houver
    if (!empty($_FILES['profile_picture']['name'])) {
        $uploadResult = $profileController->uploadPhoto($user_id, $_FILES['profile_picture']);
        if ($uploadResult !== true) {
            echo "Erro ao atualizar a foto de perfil: " . $uploadResult;
        }
    }

    // Atualização da bio
    if ($profileController->updateBio($user_id, $newBio)) {
        // Redirecionar para o perfil atualizado
        header("Location: user_profile.php");
        exit();
    } else {
        echo "Erro ao atualizar a bio.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="../css/edit_profile.css">
</head>
<body>

<div id="edit-profile">
<h1>Editar Perfil</h1>
    <form action="edit_profile.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="profile_picture">Foto de Perfil:</label>
            <input type="file" name="profile_picture" id="profile_picture">
        </div>
        <div class="form-group">
            <label for="bio">Bio:</label>
            <textarea name="bio" id="bio" rows="4"><?php echo htmlspecialchars($bio); ?></textarea>
        </div>
        <button type="submit">Salvar Alterações</button>

        <a href="user_dashboard.php">Voltar ao Dashboard</a>
    </form>
</div>

</body>
</html>