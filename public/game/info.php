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
    $apiResponse = file_get_contents("https://boardgamegeek.com/xmlapi2/thing?id=$game_id");
    if (!$apiResponse) {
        $error = "Game information not found.";
    }
    $imageURL = simplexml_load_string($apiResponse)->item->image;
    $description = simplexml_load_string($apiResponse)->item->description;
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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
</head>

<body>
<?php include '../header.php';?>

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
    <img src="<?= $imageURL ?>" alt="<?= $game->getName() ?>" class="img-fluid">
    <p><?= $description ?></p>
<?php endif; ?>
</div>

<?php include '../footer.php';?>
</body>

</html>
