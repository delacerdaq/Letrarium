<?php
require_once '../config/userDAO.php';
require_once '../controller/ProfileController.php'; 
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $userDAO = new UserDAO();
    $user = $userDAO->validateUser($username, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Verifica se o perfil já existe e cria se não existir
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
    <link rel="stylesheet" href="../css/login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    
    <div class="wrapper">
        <form action="login.php" method="POST">
            <a href="../view/index.php"><i class='bx bx-arrow-back'></i></a>
            <h1>Login</h1>
            <?php
            if (isset($error_message)) {
                echo "<p style='color: red; text-align: center; margin-top: 20px;'>$error_message</p>";
            }
            ?>
            <div class="input-box">
                <input type="text" name="username" placeholder="Nome de usuário" required>
                <i class='bx bxs-user'></i>
            </div>
            
            <div class="input-box">
                <input type="password" name="password" placeholder="Senha" required>
                <i class='bx bxs-lock-alt'></i>
            </div>

            <div class="remember-forgot">
                <label><input type="checkbox" name="remember">Lembre de mim</label>
                <a href="reset_password.php">Esqueceu a senha?</a>
            </div>

            <button type="submit" class="btn">Login</button>

            <button type="button" class="btn google-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M20.283 10.356h-8.327v3.451h4.792c-.446 2.193-2.313 3.453-4.792 3.453a5.27 5.27 0 0 1-5.279-5.28 5.27 5.27 0 0 1 5.279-5.279c1.259 0 2.397.447 3.29 1.178l2.6-2.599c-1.584-1.381-3.615-2.233-5.89-2.233a8.908 8.908 0 0 0-8.934 8.934 8.907 8.907 0 0 0 8.934 8.934c4.467 0 8.529-3.249 8.529-8.934 0-.528-.081-1.097-.202-1.625z"></path></svg>
            Continuar com o Google
            </button>

            <div class="register-link">
                <p>Não possui uma conta? <a href="../view/index.php">Cadastre-se aqui!</a></p>
            </div>
        </form>
    </div>

</body>
</html>
