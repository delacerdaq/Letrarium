<?php
session_start();
require_once '../controller/PoemController.php';
require_once '../controller/commentController.php';

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

// Verifica se o comentário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_text']) && isset($_POST['poem_id'])) {
    $commentController = new CommentController();
    $poemId = $_POST['poem_id'];
    $userId = $_SESSION['user_id'];  // Obtém o user_id da sessão
    $content = $_POST['comment_text'];

    $commentController->addComment($poemId, $userId, $content);

    /*
    if ($commentController->addComment($poemId, $userId, $content)) {
        echo "<p>Comentário enviado com sucesso!</p>";
    } else {
        echo "<p>Erro ao enviar o comentário.</p>";
    }
    */
}
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
        <h2>Bem-vindo(a), <?php echo htmlspecialchars($username); ?>!</h2>
        <p>Este é o seu dashboard.</p>
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
                    echo "<h3>" . htmlspecialchars($poem['title']) . "</h3>";
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
                            <?php if (!empty($poem['profile_picture']) && file_exists($poem['profile_picture'])): ?>
                                <img src="<?php echo htmlspecialchars($poem['profile_picture']); ?>" alt="Profile Picture">
                            <?php else: ?>
                                <div class="placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M12 2C6.579 2 2 6.579 2 12s4.579 10 10 10 10-4.579 10-10S17.421 2 12 2zm0 5c1.727 0 3 1.272 3 3s-1.273 3-3 3c-1.726 0-3-1.272-3-3s1.274-3 3-3zm-5.106 9.772c.897-1.32 2.393-2.2 4.106-2.2h2c1.714 0 3.209.88 4.106 2.2C15.828 18.14 14.015 19 12 19s-3.828-.86-5.106-2.228z"></path>
                                </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                            <div class="author-name">
                                <?php echo htmlspecialchars($poem['username']); ?>
                            </div>
                        </div>
                        <h3><?php echo htmlspecialchars($poem['title']); ?></h3>
                        <p style="text-align: center;"><?php echo nl2br(htmlspecialchars($poem['content'])); ?></p>

                        <div class="thumbs-container">
                            <div class="thumbs-up-icon" onclick="toggleLike(<?php echo $poem['id']; ?>)">
                                <svg id="thumb-up-<?php echo $poem['id']; ?>" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: <?php echo $poemController->hasLiked($poem['id'], $user_id) ? 'green' : 'black'; ?>;">
                                <path d="M20 8h-5.612l1.123-3.367c.202-.608.1-1.282-.275-1.802S14.253 2 13.612 2H12c-.297 0-.578.132-.769.36L6.531 8H4c-1.103 0-2 .897-2 2v9c0 1.103.897 2 2 2h13.307a2.01 2.01 0 0 0 1.873-1.298l2.757-7.351A1 1 0 0 0 22 12v-2c0-1.103-.897-2-2-2zM4 10h2v9H4v-9zm16 1.819L17.307 19H8V9.362L12.468 4h1.146l-1.562 4.683A.998.998 0 0 0 13 10h7v1.819z"></path>
                                </svg>
                            </div>

                            <div class="thumbs-down-icon" onclick="toggleDislike(<?php echo $poem['id']; ?>)">
                                <svg id="thumb-down-<?php echo $poem['id']; ?>" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: <?php echo $poemController->hasLiked($poem['id'], $user_id) ? 'black' : 'red'; ?>;">
                                <path d="M20 3H6.693A2.01 2.01 0 0 0 4.82 4.298l-2.757 7.351A1 1 0 0 0 2 12v2c0 1.103.897 2 2 2h5.612L8.49 19.367a2.004 2.004 0 0 0 .274 1.802c.376.52.982.831 1.624.831H12c.297 0 .578-.132.769-.36l4.7-5.64H20c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2zm-8.469 17h-1.145l1.562-4.684A1 1 0 0 0 11 14H4v-1.819L6.693 5H16v9.638L11.531 20zM18 14V5h2l.001 9H18z"></path>
                                </svg>
                            </div>
                        </div>
             
                        <small>Número de curtidas: <?php echo $poemController->countLikes($poem['id']); ?> </small>

                        <small>Categoria: <?php echo htmlspecialchars($poem['category_name']); ?></small>

                        <div class="tags" style="margin-top: 20px;">
                        <strong>Tags: 
                        <?php
                            $tagsArray = explode(',', $poem['tags']); // Supondo que 'tags' seja uma string separada por vírgulas
                        foreach ($tagsArray as $tag) {
                        echo '<a href="poems_by_tag.php?tag=' . urlencode(trim($tag)) . '">' . htmlspecialchars(trim($tag)) . '</a> ';
                        }
                        ?>
                        </strong>
                        </div>

                        <!-- Botão para mostrar o pop-up de comentários -->
                        <button onclick="toggleCommentsPopup(<?php echo $poem['id']; ?>)">Ver Comentários</button>

                        <!-- Pop-up de Comentários -->
                        <div id="comments-popup-<?php echo $poem['id']; ?>" class="comments-popup" style="display: none;">
                            <div class="popup-header">
                                <strong>Comentários</strong>
                                <button onclick="toggleCommentsPopup(<?php echo $poem['id']; ?>)">Fechar</button>
                            </div>

                        <div class="popup-content">
                        <form method="POST" action="user_dashboard.php">
                            <textarea name="comment_text" placeholder="Escreva seu comentário"></textarea>
                            <input type="hidden" name="poem_id" value="<?php echo $poem['id']; ?>">
                            <button type="submit">Enviar Comentário</button>
                        </form>

                        <div class="comments">
                        <?php
                        $commentController = new CommentController();
                        $comments = $commentController->getCommentsByPoemId($poem['id']);
                        foreach ($comments as $comment) {
                            echo "<div class='comment'>";

                            // Exibindo a foto de perfil e o nome do usuário
                            echo "<div class='comment-header'>";
        
                            // Foto de perfil ou placeholder
                            echo "<div class='comment-author-picture'>";
                            if (!empty($poem['profile_picture']) && file_exists($poem['profile_picture'])) {
                                echo "<img src='" . htmlspecialchars($comment['profile_picture']) . "' alt='Foto de perfil' class='profile-picture' />";
                            } else {
                                echo "<div class='placeholder'>";
                                echo '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"><path d="M12 2C6.579 2 2 6.579 2 12s4.579 10 10 10 10-4.579 10-10S17.421 2 12 2zm0 5c1.727 0 3 1.272 3 3s-1.273 3-3 3c-1.726 0-3-1.272-3-3s1.274-3 3-3zm-5.106 9.772c.897-1.32 2.393-2.2 4.106-2.2h2c1.714 0 3.209.88 4.106 2.2C15.828 18.14 14.015 19 12 19s-3.828-.86-5.106-2.228z"></path></svg>';
                                echo "</div>";
                            }
                            echo "</div>";

                            // Nome do usuário
                            echo "<p class='user-name'>" . htmlspecialchars($comment['name']) . "</p>";

                            echo "</div>"; // Fecha div comment-header

                            // Conteúdo do comentário
                            echo "<p class='comment-content'>" . nl2br(htmlspecialchars($comment['content'])) . "</p>";

                            // Data do comentário
                            echo "<small class='comment-date'>" . $comment['created_at'] . "</small>";

                            echo "</div>"; // Fecha div comment
                        }
                        ?>
                        </div>
                        </div>
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

<script src="../js/like.js"></script>

<script src="../js/comments.js"></script>

</body>
</html>