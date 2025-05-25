<?php
session_start();
require_once '../controller/PoemController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$poemController = new PoemController();
$categories = $poemController->getCategories();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    
    $poem = new Poem(
        $_POST['title'],
        $_POST['content'],
        $_POST['visibility'],
        $userId,
        $_POST['category']
    );

    $validationResult = $poemController->validatePoem($poem);
    
    if ($validationResult === true) {
        $resultMessage = $poemController->savePoem(
            $poem->getTitle(),
            $poem->getContent(),
            $poem->getVisibility(),
            $poem->getAuthorId(),
            $poem->getCategoryId(),
            $_POST['tags']
        );
        
        if ($resultMessage === "Poema salvo com sucesso.") {
            $successMessage = "Poema publicado com sucesso!";
        } else {
            $errorMessage = "Erro ao publicar poema: " . $resultMessage;
        }
    } else {
        $errorMessage = $validationResult;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Publicar Poema</title>
</head>
<body class="bg-[#fef9f2] min-h-screen flex items-center justify-center px-4">
  <div class="w-full mt-10 mb-10 max-w-lg bg-white p-8 rounded-lg shadow-lg text-gray-800 border border-gray-200">
    <h1 class="text-3xl font-bold mb-6 text-center text-purple-700">Publicar Poema</h1>

    <?php
    if (isset($successMessage)) {
        echo "<p class='text-green-600 mb-4 text-center'>$successMessage</p>";
    } elseif (isset($errorMessage)) {
        echo "<p class='text-red-500 mb-4 text-center'>$errorMessage</p>";
    }
    ?>

    <form action="publish_poem.php" method="POST" class="space-y-5">
      <div>
        <label for="title" class="block mb-1 font-semibold">Título:</label>
        <input
          type="text"
          id="title"
          name="title"
          required
          class="w-full rounded-md border border-gray-300 bg-white text-gray-800 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
        />
      </div>

      <div>
        <label for="content" class="block mb-1 font-semibold">Conteúdo do Poema:</label>
        <textarea
          id="content"
          name="content"
          rows="10"
          required
          class="w-full rounded-md border border-gray-300 bg-white text-gray-800 px-3 py-2 resize-y focus:outline-none focus:ring-2 focus:ring-purple-500"
        ></textarea>
      </div>

      <div>
        <label for="visibility" class="block mb-1 font-semibold">Visibilidade:</label>
        <select
          id="visibility"
          name="visibility"
          required
          class="w-full rounded-md border border-gray-300 bg-white text-gray-800 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
        >
          <option value="public">Público</option>
          <option value="restricted">Restrito</option>
        </select>
      </div>

      <div>
        <label for="category" class="block mb-1 font-semibold">Categoria:</label>
        <select
          id="category"
          name="category"
          required
          class="w-full rounded-md border border-gray-300 bg-white text-gray-800 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
        >
          <?php foreach ($categories as $category): ?>
            <option value="<?php echo htmlspecialchars($category['id']); ?>">
              <?php echo htmlspecialchars($category['name']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label for="tags" class="block mb-1 font-semibold">Tags (separadas por vírgula):</label>
        <input
          type="text"
          id="tags"
          name="tags"
          class="w-full rounded-md border border-gray-300 bg-white text-gray-800 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
        />
      </div>

      <button
        type="submit"
        class="w-full bg-purple-600 hover:bg-purple-700 transition-colors py-3 rounded-md font-semibold text-white">
        Publicar
      </button>
    </form>

    <div class="mt-6 text-center">
      <a href="user_dashboard.php" class="text-purple-600 hover:underline font-medium">
        Voltar ao Dashboard
      </a>
    </div>
  </div>

  <script src="https://cdn.tailwindcss.com"></script>
</body>
</html>