<?php
require_once '../config/userDAO.php';
require_once '../controller/ProfileController.php'; 
require_once '../controller/LoadingController.php';
session_start();

$loadingController = LoadingController::getInstance();
$loadingController->startLoading();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $userDAO = new UserDAO();
    $user = $userDAO->validateUser($username, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        $profileController = new ProfileController();
        $profile = $profileController->getProfileByUserId($user['id']);
        
        if (empty($profile)) {
            $profileController->createProfile($user['id']);
        }

        header("Location: user_dashboard.php");
        exit();
    } else {
        $error_message = "Senha ou usuário inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body class="bg-[#fef9f2] min-h-screen flex items-center justify-center px-4">

  <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg border border-gray-200">
    <form action="login.php" method="POST" class="space-y-6">

      <div class="relative mb-4">
        <a href="../view/index.php" class="absolute left-0 top-1 text-purple-600 text-2xl hover:text-purple-800">
            <i class='bx bx-arrow-back'></i>
        </a>
        <h1 class="text-2xl font-bold text-purple-700 text-center">Login</h1>
    </div>


      <?php if (isset($error_message)): ?>
        <p class="text-center text-sm text-red-600"><?= $error_message ?></p>
      <?php endif; ?>

      <div class="flex items-center gap-2 border border-gray-300 rounded-md px-3 py-2 bg-white">
        <i class='bx bxs-user text-gray-500 text-xl'></i>
        <input
          type="text"
          name="username"
          placeholder="Nome de usuário"
          required
          class="w-full outline-none text-gray-800 placeholder-gray-400"
        />
      </div>

      <div class="flex items-center gap-2 border border-gray-300 rounded-md px-3 py-2 bg-white">
        <i class='bx bxs-lock-alt text-gray-500 text-xl'></i>
        <input
          type="password"
          name="password"
          placeholder="Senha"
          required
          class="w-full outline-none text-gray-800 placeholder-gray-400"
        />
      </div>

      <div class="flex justify-between text-sm text-gray-600">
        <label class="flex items-center gap-1">
          <input type="checkbox" name="remember" class="accent-purple-600">
          Lembre de mim
        </label>
        <a href="reset_password.php" class="text-purple-600 hover:underline">Esqueceu a senha?</a>
      </div>

      <button
        type="submit"
        class="w-full py-2 bg-purple-600 hover:bg-purple-700 transition-colors text-white font-semibold rounded-md">
        Login
      </button>

      <button
        type="button"
        class="w-full py-2 border border-gray-300 hover:bg-gray-100 flex items-center justify-center gap-2 text-gray-700 font-semibold rounded-md">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
          viewBox="0 0 24 24">
          <path
            d="M20.283 10.356h-8.327v3.451h4.792c-.446 2.193-2.313 3.453-4.792 3.453a5.27 5.27 0 0 1-5.279-5.28 5.27 5.27 0 0 1 5.279-5.279c1.259 0 2.397.447 3.29 1.178l2.6-2.599c-1.584-1.381-3.615-2.233-5.89-2.233a8.908 8.908 0 0 0-8.934 8.934 8.907 8.907 0 0 0 8.934 8.934c4.467 0 8.529-3.249 8.529-8.934 0-.528-.081-1.097-.202-1.625z">
          </path>
        </svg>
        Continuar com o Google
      </button>

      <div class="text-center text-sm text-gray-700 mt-4">
        <p>Não possui uma conta? 
          <a href="../view/index.php" class="text-purple-600 hover:underline">Cadastre-se aqui!</a>
        </p>
      </div>

    </form>
  </div>

  <script src="../js/loading.js"></script>
</body>
</html>
