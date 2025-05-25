<?php
session_start();
require_once '../controller/PoemController.php';
require_once '../controller/commentController.php';
require_once '../controller/LoadingController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$loadingController = LoadingController::getInstance();
$loadingController->startLoading();

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$poemController = new PoemController();
$poems = [];

$keyword = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($keyword)) {
    $poems = $poemController->searchPoems($keyword);
} else {
    $poems = $poemController->getAllPoemsWithTagsAndProfilePictures();
}

$categories = $poemController->getCategories();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_text']) && isset($_POST['poem_id'])) {
    $commentController = new CommentController();
    $poemId = $_POST['poem_id'];
    $userId = $_SESSION['user_id'];
    $content = $_POST['comment_text'];

    $commentController->addComment($poemId, $userId, $content);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

<div id="header" class="bg-purple-700 text-white p-4 shadow-md">
    <nav class="flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="links flex gap-4 text-white font-medium">
            <a href="challenges.php" class="hover:underline">desafios</a>
            <a href="publish_poem.php" class="hover:underline">criar</a>
        </div>

        <div id="search">
            <form action="user_dashboard.php" method="GET" class="flex items-center">
                <input
                    type="text"
                    name="search"
                    placeholder="Pesquisar poemas..."
                    value="<?php echo htmlspecialchars($keyword); ?>"
                    class="rounded-l-full px-6 py-2 text-black placeholder-gray-500 focus:outline-none border border-white border-r-0">
                <button
                    type="submit"
                    class="bg-white text-purple-600 font-semibold px-5 py-2 rounded-r-full hover:bg-purple-100 transition border border-white border-l-0">
                    Pesquisar
                </button>
            </form>
        </div>

        <div id="link-profile">
            <a href="user_profile.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" class="fill-white hover:fill-purple-200 transition">
                    <path d="M12 2C6.579 2 2 6.579 2 12s4.579 10 10 10 10-4.579 10-10S17.421 2 12 2zm0 5c1.727 0 3 1.272 3 3s-1.273 3-3 3c-1.726 0-3-1.272-3-3s1.274-3 3-3zm-5.106 9.772c.897-1.32 2.393-2.2 4.106-2.2h2c1.714 0 3.209.88 4.106 2.2C15.828 18.14 14.015 19 12 19s-3.828-.86-5.106-2.228z"></path>
                </svg>
            </a>
        </div>
    </nav>
</div>

<div id="welcome" class="bg-purple-300 text-purple-900 py-4 flex justify-center items-center gap-10">
    <p class="text-l font-semibold">Bem-vindo ao nosso site de poesia!</p>
    <a href="filter.php" id="categories" class="text-white bg-purple-700 px-6 py-2 rounded-full hover:bg-purple-800 transition whitespace-nowrap">
        Filtrar por Categoria
    </a>
</div>

<div id="welcome-section" class="max-w-md mt-10 mx-auto bg-purple-50 border border-purple-300 rounded-lg p-6 text-center shadow-sm">
    <h2 class="text-2xl font-semibold text-purple-800 mb-2">
        Bem-vindo(a), <?php echo htmlspecialchars($username); ?>!
    </h2>
    <p class="text-purple-700 mb-4">Este é o seu dashboard.</p>
    <a href="../view/logout.php" 
       class="inline-block bg-purple-700 text-white px-6 py-2 rounded-full font-semibold hover:bg-purple-800 transition">
       Logout
    </a>
</div>

<?php if (!empty($keyword)): ?>
    <div class="bg-[#fffbea] mt-10 min-h-screen py-10 px-4 sm:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-2xl sm:text-3xl font-semibold text-purple-800 mb-8">
                Resultados da Pesquisa para: 
                <span class="text-purple-600 font-bold">
                    "<?php echo htmlspecialchars($keyword); ?>"
                </span>
            </h2>

            <?php
            if (!empty($poems)) {
                foreach ($poems as $poem) {
                    echo "<div class='bg-white rounded-xl shadow-md p-6 mb-6 text-left border-l-4 border-purple-400 hover:shadow-lg transition'>";
                    echo "<h3 class='text-xl font-bold text-purple-700 mb-2'>" . htmlspecialchars($poem['title']) . "</h3>";
                    echo "<p class='text-gray-700 whitespace-pre-line'>" . nl2br(htmlspecialchars($poem['content'])) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p class='text-gray-600 text-lg mt-4'>Nenhum poema encontrado para a pesquisa: 
                        <span class='font-semibold text-purple-700'>" . htmlspecialchars($keyword) . "</span>
                      </p>";
            }
            ?>
        </div>
    </div>
<?php else: ?>

<div id="poems-section" class="flex justify-center mt-10">
  <ul class="flex flex-wrap justify-center gap-6 max-w-6xl">
    <?php if (!empty($poems)): ?>
      <?php foreach ($poems as $poem): ?>
        <li class="bg-[#fef9f2] rounded-lg shadow-md p-6 w-72 flex flex-col">
          <!-- Linha 1: foto + nome -->
          <div class="flex justify-between items-center mb-3">
            <div class="author-picture w-12 h-12 rounded-full overflow-hidden flex-shrink-0">
              <?php if (!empty($poem['profile_picture']) && file_exists($poem['profile_picture'])): ?>
                <img src="<?php echo htmlspecialchars($poem['profile_picture']); ?>" alt="Profile Picture" class="w-full h-full object-cover">
              <?php else: ?>
                <div class="flex items-center justify-center w-full h-full bg-gray-200 text-gray-400">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6">
                    <path d="M12 2C6.579 2 2 6.579 2 12s4.579 10 10 10 10-4.579 10-10S17.421 2 12 2zm0 5c1.727 0 3 1.272 3 3s-1.273 3-3 3c-1.726 0-3-1.272-3-3s1.274-3 3-3zm-5.106 9.772c.897-1.32 2.393-2.2 4.106-2.2h2c1.714 0 3.209.88 4.106 2.2C15.828 18.14 14.015 19 12 19s-3.828-.86-5.106-2.228z"></path>
                  </svg>
                </div>
              <?php endif; ?>
            </div>
            <div class="author-name font-semibold text-gray-800 ml-3 flex-1 text-right truncate">
              <?php echo htmlspecialchars($poem['username']); ?>
            </div>
          </div>

          <h3 class="text-lg font-semibold mb-2 break-words"><?php echo htmlspecialchars($poem['title']); ?></h3>

          <p class="text-center text-gray-700 mb-3 whitespace-pre-wrap break-words overflow-visible"><?php echo nl2br(htmlspecialchars($poem['content'])); ?></p>

          <div class="flex justify-center items-center space-x-6 mb-2">
            <div class="cursor-pointer" onclick="toggleLike(<?php echo $poem['id']; ?>)">
              <svg id="thumb-up-<?php echo $poem['id']; ?>" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: <?php echo $poemController->hasLiked($poem['id'], $user_id) ? 'green' : 'black'; ?>;">
                <path d="M20 8h-5.612l1.123-3.367c.202-.608.1-1.282-.275-1.802S14.253 2 13.612 2H12c-.297 0-.578.132-.769.36L6.531 8H4c-1.103 0-2 .897-2 2v9c0 1.103.897 2 2 2h13.307a2.01 2.01 0 0 0 1.873-1.298l2.757-7.351A1 1 0 0 0 22 12v-2c0-1.103-.897-2-2-2zM4 10h2v9H4v-9zm16 1.819L17.307 19H8V9.362L12.468 4h1.146l-1.562 4.683A.998.998 0 0 0 13 10h7v1.819z"></path>
              </svg>
            </div>
            <div class="cursor-pointer" onclick="toggleDislike(<?php echo $poem['id']; ?>)">
              <svg id="thumb-down-<?php echo $poem['id']; ?>" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: <?php echo $poemController->hasLiked($poem['id'], $user_id) ? 'black' : 'red'; ?>;">
                <path d="M20 3H6.693A2.01 2.01 0 0 0 4.82 4.298l-2.757 7.351A1 1 0 0 0 2 12v2c0 1.103.897 2 2 2h5.612L8.49 19.367a2.004 2.004 0 0 0 .274 1.802c.376.52.982.831 1.624.831H12c.297 0 .578-.132.769-.36l4.7-5.64H20c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2zm-8.469 17h-1.145l1.562-4.684A1 1 0 0 0 11 14H4v-1.819L6.693 5H16v9.638L11.531 20zM18 14V5h2l.001 9H18z"></path>
              </svg>
            </div>
          </div>

          <small class="block text-center text-gray-600 mb-2">Número de curtidas: <?php echo $poemController->countLikes($poem['id']); ?></small>

          <div class="tags text-center text-sm mb-4">
            <strong>Tags: </strong>
            <?php
            if (!empty($poem['tags'])) {
                $tagsArray = explode(',', $poem['tags']);
                foreach ($tagsArray as $tag) {
                    if (!empty(trim($tag))) {
                        echo '<a href="poems_by_tag.php?tag=' . urlencode(trim($tag)) . '" class="text-purple-600 hover:underline mx-1">' . htmlspecialchars(trim($tag)) . '</a>';
                    }
                }
            } else {
                echo '<span class="text-gray-500">Nenhuma tag</span>';
            }
            ?>
          </div>

          <div class="flex justify-center">
            <button onclick="toggleCommentsPopup(<?php echo $poem['id']; ?>)" class="bg-purple-600 hover:bg-purple-700 text-white py-1 px-4 rounded">
              Ver Comentários
            </button>
          </div>

          <div id="comments-popup-<?php echo $poem['id']; ?>" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg p-6 max-w-lg w-full mx-4 max-h-[80vh] overflow-y-auto">
              <div class="flex justify-between items-center mb-4 sticky top-0 bg-white pb-2">
                <h3 class="text-xl font-semibold text-gray-800">Comentários</h3>
                <button onclick="toggleCommentsPopup(<?php echo $poem['id']; ?>)" class="text-gray-500 hover:text-gray-700">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              
              <div id="comments-list-<?php echo $poem['id']; ?>" class="space-y-4 mb-4">
              </div>

              <form onsubmit="submitComment(event, <?php echo $poem['id']; ?>)" class="mt-4 sticky bottom-0 bg-white pt-2">
                <textarea 
                  name="comment_text" 
                  class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                  placeholder="Escreva seu comentário..."
                  rows="3"
                  required></textarea>
                <button 
                  type="submit"
                  class="mt-2 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                  Enviar Comentário
                </button>
              </form>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center w-full">Nenhum poema encontrado.</p>
    <?php endif; ?>
  </ul>
</div>
<?php endif; ?>

    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        (function () {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/670db84b2480f5b4f58d693c/1ia6pfq8g';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>

    <script src="../js/like.js"></script>
    <script src="../js/comments.js"></script>
    <script src="../js/loading.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    </body>
</html>