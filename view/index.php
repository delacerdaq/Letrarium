<?php
require_once '../controller/UserController.php';

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $terms = isset($_POST['terms']) ? 1 : 0;

    $userController = new UserController();
    $success = $userController->registerUser($username, $name, $email, $password, $terms);

    if ($success) {
        $successMessage = "Registro feito com sucesso!";
    } else {
        $errorMessage = "Não foi possível concluir o cadastro.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <title>Landing Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .carousel {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .carousel-container {
            position: relative;
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    <!-- Header -->
    <header class="bg-purple-700 text-white p-4">
        <nav class="flex justify-between items-center container mx-auto">
            <div class="flex items-center space-x-2">
                <div class="bg-white rounded-full h-10 w-10 flex items-center justify-center">
                    <p class="text-purple-700 font-bold">L</p>
                </div>
                <p class="font-semibold">Letrarium</p>
            </div>
            <div class="space-x-4">
                <a href="#" class="hover:text-gray-300">Home</a>
                <a href="#" class="hover:text-gray-300">Explorar</a>
                <a href="#" class="hover:text-gray-300">Cadastrar</a>
                <a href="../view/login.php" class="hover:text-gray-300">Login</a>
            </div>
        </nav>
    </header>

    <!-- Intro Section -->
<section class="bg-purple-700 py-12">
    <div class="container mx-auto">
        <div class="carousel-container w-full h-64 md:h-96 overflow-hidden rounded-lg">
            <!-- Carousel -->
            <div id="carousel" class="carousel w-full h-full">
                <div class="w-full h-full flex-shrink-0 flex flex-col justify-center items-center bg-purple-700 text-center px-6">
                    <h1 class="text-2xl md:text-4xl font-bold text-white">Nós somos apaixonados por escrita</h1>
                    <p class="text-md md:text-lg mt-4 text-gray-200">
                        Leia poemas autorais e escreva os seus próprios, permitindo que cada palavra seja uma expressão única da sua alma.
                    </p>
                    <a href="#" class="mt-6 px-4 py-2 bg-white text-purple-700 rounded hover:bg-gray-200 border border-gray-100">Comece agora!</a>
                </div>
                <div class="w-full h-full flex-shrink-0 flex flex-col justify-center items-center bg-purple-700 text-center px-6">
                    <h1 class="text-2xl md:text-4xl font-bold text-white">Explore o mundo da poesia</h1>
                    <p class="text-md md:text-lg mt-4 text-gray-200">
                        Descubra novos talentos, leia histórias emocionantes e compartilhe sua paixão por palavras.
                    </p>
                    <a href="#" class="mt-6 px-4 py-2 bg-white text-purple-700 rounded hover:bg-gray-200 border border-gray-100">Explore agora!</a>
                </div>
                <div class="w-full h-full flex-shrink-0 flex flex-col justify-center items-center bg-purple-700 text-center px-6">
                    <h1 class="text-2xl md:text-4xl font-bold text-white">Faça parte da nossa comunidade</h1>
                    <p class="text-md md:text-lg mt-4 text-gray-200">
                        Conecte-se com escritores e leitores de todo o mundo em um só lugar.
                    </p>
                    <a href="#" class="mt-6 px-4 py-2 bg-white text-purple-700 rounded hover:bg-gray-200 border border-gray-100">Junte-se a nós!</a>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Conteúdo Abaixo -->
    <section class="py-12 bg-white">
        <div class="container mx-auto">
            <h2 class="text-2xl font-bold text-gray-800 text-center">Destaques</h2>
            <p class="text-center mt-4 text-gray-600">
                Explore os poemas mais populares da nossa plataforma.
            </p>
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 bg-purple-100 rounded-lg shadow-md">
                    <h3 class="font-semibold text-lg text-purple-700">Poema Inspirador</h3>
                    <p class="mt-2 text-gray-600">"O céu azul reflete a alma pura dos sonhadores."</p>
                </div>
                <div class="p-6 bg-purple-100 rounded-lg shadow-md">
                    <h3 class="font-semibold text-lg text-purple-700">A Poesia da Vida</h3>
                    <p class="mt-2 text-gray-600">"Cada palavra é um passo em direção à eternidade."</p>
                </div>
                <div class="p-6 bg-purple-100 rounded-lg shadow-md">
                    <h3 class="font-semibold text-lg text-purple-700">Sentimentos Profundos</h3>
                    <p class="mt-2 text-gray-600">"Os versos cantam o que os corações não conseguem dizer."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Login Section -->
<section class="bg-purple-50 py-12">
    <div class="container mx-auto flex justify-center items-center">
        <div class="w-full md:w-1/2 mt-8 md:mt-0 bg-white p-8 rounded-lg shadow-lg border border-gray-300">
            <form action="index.php" method="POST" class="space-y-6">
                <h1 class="text-2xl font-bold text-purple-800 text-center mb-6">Cadastro</h1>

                <?php if ($successMessage): ?>
                    <p class="text-green-600 text-center mb-4"><?= $successMessage ?></p>
                <?php endif; ?>
                <?php if ($errorMessage): ?>
                    <p class="text-red-600 text-center mb-4"><?= $errorMessage ?></p>
                <?php endif; ?>

                <div>
                    <input type="text" name="username" placeholder="Nome de usuário" 
                        class="w-full border border-gray-300 rounded-full p-3 focus:ring-2 focus:ring-purple-600 focus:outline-none" required>
                </div>
                <div>
                    <input type="text" name="name" placeholder="Nome" 
                        class="w-full border border-gray-300 rounded-full p-3 focus:ring-2 focus:ring-purple-600 focus:outline-none" required>
                </div>
                <div>
                    <input type="email" name="email" placeholder="Email" 
                        class="w-full border border-gray-300 rounded-full p-3 focus:ring-2 focus:ring-purple-600 focus:outline-none" required>
                </div>
                <div>
                    <input type="password" name="password" placeholder="Senha" 
                        class="w-full border border-gray-300 rounded-full p-3 focus:ring-2 focus:ring-purple-600 focus:outline-none" required>
                </div>
                <div class="text-gray-600">
                    <label>
                        <input type="checkbox" name="terms" class="mr-2">
                        Eu concordo com os <a href="#" class="text-purple-600 hover:underline">Termos & Condições</a>
                    </label>
                </div>
                <button type="submit" 
                    class="block w-full bg-purple-600 text-white py-3 px-4 rounded-full hover:bg-purple-700 focus:ring-4 focus:ring-purple-400">
                    Cadastrar
                </button>
            </form>
        </div>
    </div>
</section>


    <script>
        // Lógica do carrossel
        const carousel = document.getElementById('carousel');
        let index = 0;

        function showNextSlide() {
            index = (index + 1) % 3; // Total de 3 slides
            const offset = -index * 100; // Move o carrossel
            carousel.style.transform = `translateX(${offset}%)`;
        }

        // Avança para o próximo slide a cada 5 segundos
        setInterval(showNextSlide, 5000);
    </script>
</body>
</html>
