<?php
require_once '../controller/ChallengeController.php';

$challengeController = new ChallengeController();

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Checagem de admin
$isAdmin = $challengeController->isAdmin($user_id);

// Busca de desafios do banco
$challenges = $challengeController->fetchAllChallenges();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenges</title>
    <link rel="stylesheet" href="../css/challenge.css">
</head>
<body>
    <div class="container">
        <h1>Challenges</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tema</th>
                    <th>Descrição</th>
                    <th>Mês/Ano</th>
                    <th>Postado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($challenges)): ?>
                    <?php foreach ($challenges as $challenge): ?>
                        <tr>
                            <td><?= htmlspecialchars($challenge['id']) ?></td>
                            <td><?= htmlspecialchars($challenge['theme']) ?></td>
                            <td><?= htmlspecialchars($challenge['description']) ?></td>
                            <td><?= htmlspecialchars($challenge['month_year']) ?></td>
                            <td><?= htmlspecialchars($challenge['created_at']) ?></td>
                            <td class="actions">
                                <a href="publish_challenge_poem.php?challenge_id=<?= $challenge['id'] ?>" class="button">Submit Poem</a>
                                <a href="view_challenge_poems.php?challenge_id=<?= $challenge['id'] ?>" class="button">View Poems</a>
                                <?php if ($isAdmin): ?>
                                    <a href="edit_challenge.php?challenge_id=<?= $challenge['id'] ?>" class="button">Edit</a>
                                    <a href="delete_challenge.php?challenge_id=<?= $challenge['id'] ?>" class="button delete">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Nenhum desafio encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
