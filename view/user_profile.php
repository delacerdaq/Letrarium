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

$profilePicture = !empty($profile['profile_picture']) ? $profile['profile_picture'] : null;
$bio = !empty($profile['bio']) ? $profile['bio'] : '';

$poemController = new PoemController();
$poems = $poemController->getPoemsByUser($user_id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body class="bg-white text-gray-800 min-h-screen px-4 py-8">
  <div class="max-w-4xl mx-auto space-y-8">

    <div id="profile-top" class="flex flex-col items-center space-y-4">
      <div id="profile-picture" class="relative">
      <?php if ($profilePicture && file_exists($profilePicture)): ?>
          <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture" class="w-32 h-32 rounded-full object-cover border-4 border-purple-500 shadow-md">
        <?php else: ?>
          <div class="w-32 h-32 flex items-center justify-center rounded-full bg-gray-200 border-4 border-purple-500 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="gray" viewBox="0 0 24 24">
              <path d="M12 2C6.579 2 2 6.579 2 12s4.579 10 10 10 10-4.579 10-10S17.421 2 12 2zm0 5c1.727 0 3 1.272 3 3s-1.273 3-3 3c-1.726 0-3-1.272-3-3s1.274-3 3-3zm-5.106 9.772c.897-1.32 2.393-2.2 4.106-2.2h2c1.714 0 3.209.88 4.106 2.2C15.828 18.14 14.015 19 12 19s-3.828-.86-5.106-2.228z"></path>
            </svg>
          </div>
        <?php endif; ?>

        <a href="edit_profile.php" id="edit-picture" class="absolute bottom-0 right-0 bg-gray-300 text-gray-700 p-2 rounded-full shadow hover:bg-gray-400">
          ✎
        </a>
      </div>

      <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($username); ?></h1>
      <p class="text-sm text-gray-600 max-w-md text-center"><?php echo htmlspecialchars($bio); ?></p>
    </div>

    <div id="profile-options" class="grid sm:grid-cols-2 md:grid-cols-3 gap-4 text-center">
      <a href="edit_profile.php" class="bg-purple-500 hover:bg-purple-600 text-white transition-colors py-2 px-4 rounded shadow">Editar Perfil</a>
      <a href="reset_password.php" class="bg-purple-500 hover:bg-purple-600 text-white transition-colors py-2 px-4 rounded shadow">Mudar Senha</a>
      <a href="change_email.php" class="bg-purple-500 hover:bg-purple-600 text-white transition-colors py-2 px-4 rounded shadow">Mudar Email</a>
      <a href="settings.php" class="bg-purple-500 hover:bg-purple-600 text-white transition-colors py-2 px-4 rounded shadow">Configurações</a>
      <a href="preferences.php" class="bg-purple-500 hover:bg-purple-600 text-white transition-colors py-2 px-4 rounded shadow">Preferências</a>
      <a href="../view/user_dashboard.php" class="bg-purple-500 hover:bg-purple-600 text-white transition-colors py-2 px-4 rounded shadow">Voltar ao Dashboard</a>
      <a href="../view/logout.php" class="bg-purple-500 hover:bg-purple-600 text-white transition-colors py-2 px-4 rounded shadow col-span-full">Logout</a>
    </div>

    <div id="poems-section">
      <h2 class="text-xl font-semibold mb-4">Meus Poemas</h2>

      <div class="poems-container grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php if (!empty($poems)): ?>
          <?php foreach ($poems as $poem): ?>
            <div class="poem bg-white border border-gray-200 p-4 rounded-lg shadow-sm hover:shadow-md transition">
              <h3 class="text-lg font-bold mb-2 text-purple-800"><?php echo htmlspecialchars($poem['title']); ?></h3>
              <p class="text-sm text-gray-700 mb-2"><?php echo nl2br(htmlspecialchars($poem['content'])); ?></p>
              <small class="block mb-2 text-purple-500">Categoria: <?php echo htmlspecialchars($poem['category_name']); ?></small>
              <div class="text-sm space-x-2">
                <a href="edit_poem.php?id=<?php echo $poem['id']; ?>" class="text-purple-600 hover:underline">Editar</a>
                <span class="text-gray-400">|</span>
                <a href="delete_poem.php?id=<?php echo $poem['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este poema?');" class="text-red-500 hover:underline">Excluir</a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-center text-gray-500">Você ainda não publicou nenhum poema.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script src="https://cdn.tailwindcss.com"></script>
</body>


