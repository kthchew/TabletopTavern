#!/usr/local/bin/php
<?php
require '../../vendor/autoload.php';
session_start();
$game_id = isset($_GET['game_id']) ? $_GET['game_id'] : null;
if (!isset($game_id)) {
    $error = "Game not found.";
} else {
    $game = Tabletop\Entities\Game::getGameById($game_id);
    if (!$game) {
        $error = "Game not found.";
    }
}
define('__HEADER_FOOTER_PHP__', true);
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

    <link rel="stylesheet" href="../css/style.css">
    <style>
        .heart {
            font-size: 24px;
            cursor: pointer;
        }

        .heart:hover,
        .heart.active {
            color: red;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hearts = document.querySelectorAll('.heart');
            hearts.forEach(heart => {
                heart.addEventListener('click', function() {
                    const rating = parseInt(heart.getAttribute('data-value'));
                    hearts.forEach((h, index) => {
                        if (index < rating) {
                            h.classList.add('active');
                        } else {
                            h.classList.remove('active');
                        }
                    });
                    document.getElementById('rating_value').value = rating;
                });
            });
        });

    </script>
</head>

<body>
<?php include '../header.php';?>

<div class="container mt-3">
<?php if (!isset($game)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php else: ?>
    <h1><?= $game->getName() ?></h1>
    <div>
        <div class="w-25 float-end">
            <img src="<?= $game->getImageURL() ?>" alt="<?= $game->getName() ?>" class="img-thumbnail m-3">
        </div>
        <?php if ($game->getMinPlayers() === $game->getMaxPlayers()): ?>
            <p>Players: <?= $game->getMinPlayers() ?></p>
        <?php else: ?>
            <p>Players: <?= $game->getMinPlayers() ?> - <?= $game->getMaxPlayers() ?></p>
        <?php endif; ?>
        <p>Play Time: <?= $game->getPlayTime() ?> min</p>
        <p>Minimum Age: <?= $game->getMinAge() ?></p>
        <p>Year Published: <?= $game->getYearPublished() ?></p>
        <p>Subgenres:</p>
        <ul>
            <?php foreach ($game->getSubgenres() as $subgenre): ?>
                <li><?= $subgenre ?></li>
            <?php endforeach; ?>
        </ul>
        <p>Mechanics:</p>
        <ul>
            <?php foreach ($game->getMechanics() as $mechanic): ?>
                <li><?= $mechanic ?></li>
            <?php endforeach; ?>
        </ul>
        <hr>
        <div id="rating">
            <span class="heart" data-value="1">&#x2661;</span>
            <span class="heart" data-value="2">&#x2661;</span>
            <span class="heart" data-value="3">&#x2661;</span>
            <span class="heart" data-value="4">&#x2661;</span>
            <span class="heart" data-value="5">&#x2661;</span>
        </div>
        <form action="submit_rating.php?game_id=<?= $game_id ?>" method="post">
            <input type="hidden" id="rating_value" name="rating_value" value="">
            <button type="submit">Submit Rating</button>
        </form>
        <h3>Description</h3>
        <p><?= $game->getDescription() ?></p>
    </div>

    <hr>
<!-- comments, etc can go here -->
<?php endif; ?>
</div>

<?php include '../footer.php';?>
</body>

</html>
