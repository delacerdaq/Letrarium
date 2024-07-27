<?php
require '../vendor/autoload.php'; 
use PHPMailer\PHPMailer\PHPMailer; // utilização de biblioteca
use PHPMailer\PHPMailer\Exception;

require_once '../config/userDAO.php';
require_once '../controller/PasswordRecovery.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $userDAO = new UserDAO();
    $passwordRecovery = new PasswordRecovery();

    $userInfo = $userDAO->getUserByEmail($email);

    if ($userInfo) {
        $token = $passwordRecovery->createToken($userInfo['id']);
        $recoveryLink = "http://localhost/Letrarium/view/new_password.php?token=" . $token;

        $mail = new PHPMailer(true);
        try {
            // Configurações do servidor
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = ''; // Seu e-mail do Gmail
            $mail->Password = ''; // Sua senha de aplicativo do Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Remetente e destinatário
            $mail->setFrom('', 'Letrarium');
            $mail->addAddress($email);

            // Conteúdo do e-mail
            $mail->isHTML(true);
            $mail->Charset = 'UTF-8'; // Configuração explícita da codificação
            $mail->Subject = 'Recuperação de Senha';
            $mail->Body = 'Clique no link para redefinir sua senha: <a href="' . $recoveryLink . '">' . $recoveryLink . '</a>';

            $mail->send();
            echo 'Um e-mail com as instruções de recuperação de senha foi enviado.';
        } catch (Exception $e) {
            echo 'Erro ao enviar e-mail: ', $mail->ErrorInfo;
        }
    } else {
        echo 'Nenhum usuário encontrado com esse e-mail.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar a senha</title>
</head>
<body>
    <form method="POST" action="">
    <label for="email">E-mail:</label>
    <input type="email" name="email" id="email" required>
    <button type="submit">Recuperar Senha</button>
</form>
</body>
</html>


