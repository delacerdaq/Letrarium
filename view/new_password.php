<?php
require_once '../config/PasswordDAO.php';
require_once '../config/userDAO.php';
require_once '../controller/LoadingController.php';

$loadingController = LoadingController::getInstance();
$loadingController->startLoading();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];

    $passwordRecovery = new PasswordDAO();
    $resetSuccess = $passwordRecovery->resetPassword($token, $newPassword);

    if ($resetSuccess) {
        echo "Senha redefinida com sucesso!";
    } else {
        echo "Token inválido ou expirado.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova senha</title>
</head>
<body class="bg-[#fffbea] min-h-screen flex items-center justify-center font-sans text-gray-800">

<form method="POST" action="" class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
    <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">

    <h1 class="text-2xl font-bold text-purple-800 mb-6 text-center">Redefinir Senha</h1>

    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Nova Senha:</label>
    <input 
        type="password" 
        name="new_password" 
        id="new_password" 
        required 
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 mb-6"
    >

    <button 
        type="submit" 
        class="w-full bg-purple-700 hover:bg-purple-800 text-white font-semibold py-2 px-4 rounded-lg transition shadow-md">
        Redefinir Senha
    </button>

    <a href="login.php" 
        class="w-full mt-4 bg-purple-200 hover:bg-purple-300 text-purple-800 font-semibold py-2 px-4 rounded-lg transition shadow-md text-center block">
        Voltar ao login
    </a>

</form>
<script src="https://cdn.tailwindcss.com"></script>
<script src="../js/loading.js"></script>
</body>
</html>


