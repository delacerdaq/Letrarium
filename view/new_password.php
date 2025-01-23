<?php
require_once '../config/PasswordDAO.php';
require_once '../config/userDAO.php';

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
<body>
    <form method="POST" action="">
    <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
    <label for="new_password">Nova Senha:</label>
    <input type="password" name="new_password" id="new_password" required>
    <button type="submit">Redefinir Senha</button>
</form>
</body>
</html>


