<?php
session_start();
require_once '../controller/ProfileController.php';
require_once '../controller/LoadingController.php';

$loadingController = LoadingController::getInstance();
$loadingController->startLoading();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$profileController = new ProfileController();
$profile = $profileController->getProfileByUserId($user_id);

$bio = isset($profile['bio']) ? $profile['bio'] : '';
$profilePicture = !empty($profile['profile_picture']) ? $profile['profile_picture'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newBio = $_POST['bio'] ?? '';

    if (!empty($_FILES['profile_picture']['name'])) {
        $uploadResult = $profileController->uploadPhoto($user_id, $_FILES['profile_picture']);
        if ($uploadResult !== true) {
            echo "Erro ao atualizar a foto de perfil: " . $uploadResult;
        }
    }

    if ($profileController->updateBio($user_id, $newBio)) {
        header("Location: user_profile.php");
        exit();
    } else {
        echo "Erro ao atualizar a bio.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Editar Perfil</title>
</head>
<body class="bg-[#fef9f2] text-gray-800 min-h-screen flex items-center justify-center p-6">

  <div class="w-full max-w-xl bg-white rounded-2xl shadow-lg p-8 space-y-6 border border-gray-200">

    <h1 class="text-2xl font-bold text-purple-700 text-center">Editar Perfil</h1>

    <form action="edit_profile.php" method="post" enctype="multipart/form-data" class="space-y-6">

      <div>
        <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-1">Foto de Perfil:</label>
        <input type="file" name="profile_picture" id="profile_picture" class="w-full rounded border border-gray-300 p-2 file:bg-purple-500 file:text-white file:rounded file:px-4 file:py-2 file:cursor-pointer"/>
      </div>

      <div>
        <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio:</label>
        <textarea name="bio" id="bio" rows="4" class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-purple-500"><?php echo htmlspecialchars($bio); ?></textarea>
      </div>

      <div class="flex justify-between items-center">
        <a href="user_dashboard.php" class="text-purple-600 hover:underline text-sm">← Voltar ao Dashboard</a>
        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition shadow">
          Salvar Alterações
        </button>
      </div>

    </form>
  </div>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="../js/loading.js"></script>
</body>
</html>
