<?php
require_once '../model/user.php';
require_once '../config/userDAO.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $terms = isset($_POST['terms']) ? 1 : 0;

    $user = new User($username, $name, $email, $password, $terms);
    $userDAO = new UserDAO();

    $userDAO->register($user->getUsername(), $user->getName(), $user->getEmail(), $user->getPassword(), $user->getTerms());

    /*
    echo "User registered successfully!";
    */
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    
    <div id="header">
        <nav id="nav-bar">
            <div id="logo">
                <img src="" alt="">
                <p>Letrarium</p>
            </div>

            <div class="links">
                <a href="">Home</a>
                <a href="">Explorar</a>
                <a id="cadastrar" href="">Cadastrar</a>
                <a id="logar" href="../view/login.php">Login</a>
            </div>

        </nav>

        <div class="intro">
            <div id="img-intro">
                <img src="../img/books.png" alt="">
            </div>

            <div id="texto-intro">
                <h1>Nós somos apaixonados por escrita</h1>
                <p>Leia poemas autorais e escreva os seus próprios, <br>
                    permitindo que cada palavra seja uma expressão <br>
                    única da sua alma.</p>
                <a href="" id="comece-agora">Comece agora!</a>
            </div>
        </div>
    </div>

<div class="container">
    <div id="leitores" class="content">
        <h3>Para os leitores</h3>
        <p>Lorem ipsum dolor sit amet. Et galisum illo ad Quis harum qui harum 
            expedita vel expedita nulla ea aliquid ullam aut enim commodi. Ea 
            repudiandae commodi sed vero nostrum ut fugit rerum in atque quisquam 
            sit explicabo nihil aut asperiores expedita.</p>
        <a href="#" id="comece-leitores">Comece a ler</a>
    </div>

    <div id="escritores" class="content">
        <h3>Para os escritores</h3>
        <p>Lorem ipsum dolor sit amet. Et galisum illo ad Quis harum qui harum 
            expedita vel expedita nulla ea aliquid ullam aut enim commodi. Ea 
            repudiandae commodi sed vero nostrum ut fugit rerum in atque quisquam 
            sit explicabo nihil aut asperiores expedita.</p>
        <a href="#" id="comece-escritores">Comece a escrever</a>
    </div>
</div>

<section id="section-1">
    <div id="img-section-1">
        <img src="../img/img_livro_section.png" alt="">
    </div>

    <div id="text-section-1">
        <h4>Leia uma inspiração</h4>
        <p>Lorem ipsum dolor sit amet. Non sunt accusamus est aperiam velit a corrupti quia 
            non iusto dolorum ad dolorem voluptatem. Qui doloremque praesentium qui voluptatem 
            molestiae est ratione voluptatem sed sint illum id animi rerum nam neque perferendis. </p>
    </div>
</section>

<section id="section-2">
    <div id="text-section-1">
        <h4>Compartilhe seus próprios poemas</h4>
        <p>Lorem ipsum dolor sit amet. Non sunt accusamus est aperiam velit a corrupti quia non iusto dolorum
             ad dolorem voluptatem. Qui doloremque praesentium qui voluptatem molestiae est ratione voluptatem 
             sed sint illum id animi rerum nam neque perferendis. </p>
    </div>

    <div id="img-section-1">
        <img src="../img/img_woman_section.png" alt="">
    </div>
</section>

<section id="login-section">

    <div id="text-login">
        <h3>Registre-se e descubra hoje mesmo!</h3>
        <p>Lorem ipsum dolor sit amet. Non sunt accusamus est aperiam velit a corrupti 
            quia non iusto dolorum ad dolorem voluptatem. Qui doloremque praesentium qui 
            voluptatem molestiae est ratione voluptatem sed sint illum id animi rerum nam 
            neque perferendis. </p>
    </div>

    <div class="wrapper">
        <form action="index.php" method="POST">
            <a href="#"><i class='bx bx-arrow-back'></i></a>
            <h1>Cadastro</h1>
            <div class="input-box">
                <input type="text" name="username" placeholder="Nome de usuário" required>
                <i class='bx bxs-user'></i>
            </div>

            <div class="input-box">
                <input type="text" name="name" placeholder="Nome" required>
                <i class='bx bx-user'></i>
            </div>

            <div class="input-box">
                <input type="email" name="email" placeholder="Email" required>
                <i class='bx bxs-envelope'></i>
            </div>

            <div class="input-box">
                <input type="password" name="password" placeholder="Senha" required>
                <i class='bx bxs-lock-alt'></i>
            </div>

            <div class="remember-forgot">
                <label><input type="checkbox" name="terms">Eu concordo com os <a href="">Termos & Condições</a></label>
            </div>

            <button type="submit" class="btn">Cadastrar</button>

            <button type="button" class="btn google-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M20.283 10.356h-8.327v3.451h4.792c-.446 2.193-2.313 3.453-4.792 3.453a5.27 5.27 0 0 1-5.279-5.28 5.27 5.27 0 0 1 5.279-5.279c1.259 0 2.397.447 3.29 1.178l2.6-2.599c-1.584-1.381-3.615-2.233-5.89-2.233a8.908 8.908 0 0 0-8.934 8.934 8.907 8.907 0 0 0 8.934 8.934c4.467 0 8.529-3.249 8.529-8.934 0-.528-.081-1.097-.202-1.625z"></path></svg>
            Continuar com o Google
            </button>
            
            <div class="register-link">
                <p>Já possui uma conta? <a href="#">Login</a></p>
            </div>
        </form>
    </div>
</section>

</body>
</html>