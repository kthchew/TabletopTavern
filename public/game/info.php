#!/usr/local/bin/php
<?php
require '../../vendor/autoload.php';
session_start();
$game_id = $_GET['game_id'];
if (!isset($game_id)) {
    $error = "Game not found.";
} else {
    $game = Tabletop\Entities\Game::getGameById($game_id);
    if (!$game) {
        $error = "Game not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tabletop Tavern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>

<body>
<header class="container-fluid bg-dark text-white m-0">
    <h1 class="text-center py-5 mb-0">Tabletop Tavern</h1>
</header>
<nav class="navbar navbar-expand-lg bg-dark bg-opacity-75 navbar-dark py-3 justify-content-center">
    <ul class="navbar-nav justify-content-around w-75">
        <li class="nav-item"><a class="nav-link active" href="../index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="../about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="../filter.php">Filter Search</a></li>
        <li class="nav-item"><a class="nav-link" href="../creators.php">Creators</a></li>
        <?php if (isset($_SESSION['user'])): ?>
            <li class="nav-item"><a class="nav-link" href="../logout.php">Logout <?php echo $_SESSION['username'] ?></a></li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="../login.php">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div class="container mt-3">
<?php if (!isset($game)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php else: ?>
    <h1><?= $game->getName() ?></h1>
    <?php if ($game->getMinPlayers() === $game->getMaxPlayers()): ?>
        <p>Players: <?= $game->getMinPlayers() ?></p>
    <?php else: ?>
        <p>Players: <?= $game->getMinPlayers() ?> - <?= $game->getMaxPlayers() ?></p>
    <?php endif; ?>
    <p>Play Time: <?= $game->getPlayTime() ?> min</p>
    <p>Minimum Age: <?= $game->getMinAge() ?></p>
    <p>Rating: <?= $game->getRatingAverage() ?> (<?= $game->getRatingCount() ?> votes)</p>
    <p>Year Published: <?= $game->getYearPublished() ?></p>
<?php endif; ?>
</div>

</body>

</html>
