<?php
session_start();
require_once '../controller/PoemController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$poemController = new PoemController();
$poems = [];

// Inicializa o filtro de pesquisa
$keyword = isset($_GET['search']) ? $_GET['search'] : '';

// Se há uma palavra-chave, realiza a pesquisa
if (!empty($keyword)) {
    $poems = $poemController->searchPoems($keyword);
} else {
    // Busca todos os poemas quando não há pesquisa
    $poems = $poemController->getAllPoemsWithTagsAndProfilePictures();
}

$categories = $poemController->getCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../css/user_dashboard.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

    <div id="header">
        <nav>
            <div class="links">
                <a href="#">desafios</a>
                <a href="publish_poem.php">criar</a>
            </div>

            <div id="search">
                <form action="user_dashboard.php" method="GET">
                    <input type="text" name="search" placeholder="Pesquisar poemas..." value="<?php echo htmlspecialchars($keyword); ?>">
                    <button type="submit">Pesquisar</button>
                </form>
            </div>

            <div id="link-profile">
                <a href="user_profile.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(231, 231, 231, 1);"><path d="M12 2C6.579 2 2 6.579 2 12s4.579 10 10 10 10-4.579 10-10S17.421 2 12 2zm0 5c1.727 0 3 1.272 3 3s-1.273 3-3 3c-1.726 0-3-1.272-3-3s1.274-3 3-3zm-5.106 9.772c.897-1.32 2.393-2.2 4.106-2.2h2c1.714 0 3.209.88 4.106 2.2C15.828 18.14 14.015 19 12 19s-3.828-.86-5.106-2.228z"></path></svg>
                </a>
            </div>
        </nav>
    </div>

    <div id="welcome">
        <p>Bem-vindo ao nosso site de poesia!</p>
        <a href="filter.php" id="categories">Filtrar por Categoria</a>
    </div>

    <div id="welcome-section">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <p>This is your dashboard.</p>
        <a href="../view/logout.php">Logout</a>
    </div>

    <!-- Resultados da pesquisa por input -->
    <?php if (!empty($keyword)): ?>
        <div class="search-results">
            <?php
            if (!empty($poems)) {
                echo "<h2>Resultados da Pesquisa para: <strong>" . htmlspecialchars($keyword) . "</strong></h2>";
                foreach ($poems as $poem) {
                    echo "<div class='poem'>";
                    echo "<h3>" . htmlspecialchars($poem['title ']) . "</h3>";
                    echo "<p>" . nl2br(htmlspecialchars($poem['content'])) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Nenhum poema encontrado para a pesquisa: <strong>" . htmlspecialchars($keyword) . "</strong></p>";
            }
            ?>
        </div>
    <?php else: ?>
        
        <div id="poems-section">

            <?php if (!empty($poems)): ?>
                <ul>
                <?php foreach ($poems as $poem): ?>
                    <li>
                        <div class="author-info">
                            <div class="author-picture">
                                <?php if (!empty($poem['profile_picture'])): ?>
                                    <img src="<?php echo htmlspecialchars($poem['profile_picture']); ?>" alt="Profile Picture">
                                <?php else: ?>
                                    <div class="placeholder">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24"><path d="M12 2C6.579 2 2 6.579 2 12s4.579 10 10 10 10-4.579 10-10S17.421 2 12 2zm0 5c1.727 0 3 1.272 3 3s-1.273 3-3 3c-1.726 0-3-1.272-3-3s1.274-3 3-3zm-5.106 9.772c.897-1.32 2.393-2.2 4.106-2.2h2c1.714 0 3.209.88 4.106 2.2C15.828 18.14 14.015 19 12 19s-3.828-.86-5.106-2.228z"></path></svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="author-name">
                                <?php echo htmlspecialchars($poem['username']); ?>
                            </div>
                        </div>
                        <h3><?php echo htmlspecialchars($poem['title']); ?></h3>
                        <p style="text-align: center;"><?php echo nl2br(htmlspecialchars($poem['content'])); ?></p>

                        <div class="heart-icon" onclick="toggleHeart(<?php echo $poem['id']; ?>)">
                            <svg id="heart-svg-<?php echo $poem['id']; ?>" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart <?php echo $poemController->hasLiked($poem['id'], $user_id) ? 'liked' : ''; ?>">
                                <path d="M20.8 4.6c-1.7-1.7-4.4-1.7-6.1 0L12 7.3 9.3 4.6c-1.7-1.7-4.4-1.7-6.1 0-1.7 1.7-1.7 4.4 0 6.1L12 20.4l8.8-9.7c1.7-1.7 1.7-4.4 0-6.1z"/>
                            </svg>
                        </div>
                        <small>Número de curtidas: <?php echo $poemController->countLikes($poem['id']); ?> </small>

                        <small>Categoria: <?php echo htmlspecialchars($poem['category_name']); ?></small>
                        <div class="tags" style="margin-top: 20px;">
                            <strong>Tags: <a href=""><?php echo htmlspecialchars($poem['tags']); ?></a></strong> 
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <?php else: ?>
                <p>Nenhum poema encontrado.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/670db84b2480f5b4f58d693c/1ia6pfq8g';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
</script>
<!--End of Tawk.to Script-->

<script src="../js/heart.js"></script>

</body>
</html>