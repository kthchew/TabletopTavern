#!/usr/local/bin/php
<?php
require '../vendor/autoload.php';
session_start();
// get the search term from the form
$searchTerm = $_POST['searchTerm'];
use Tabletop\Entities\Game;
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

    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<?php include 'header.php';?>
<main class="container">
<h1 class="text-center py-4">Search Results</h1>
    <p class="text-center">Results for: <b><?php echo $searchTerm; ?></b></p>
    <br>
    <br>
    <div class="row row-cols-4">

            <?php
            // get the games that match the search term
            $games = Game::searchGamesByName($searchTerm);
            // first 10 games
            $games = array_slice($games, 0, 10);
            foreach ($games as $game) {
                echo "<a class=\"col\" href=\"game/info.php?game_id={$game->getId()}\">";
                echo $game->cardView();
                echo '</a>';
            }
            ?>

    </div>
</main>
<?php include 'footer.php';?>
</body>


