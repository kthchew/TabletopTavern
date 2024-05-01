#!/usr/local/bin/php
<?php
require '../vendor/autoload.php';
session_start();
$page = $_GET['page'] ?? 1;
use Tabletop\Entities\Game;
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

    <link rel="stylesheet" href="css/style.css">

    <style>
        .scroll-container {
            overflow: auto;
            background-color: #DEEDC8;
            border-radius: 10px;
            overflow-x: scroll;
        }

        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #cee1b1;
        }

        ::-webkit-scrollbar-thumb {
            background: #a2d15c;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #629716;
        }

        .row {
            flex-wrap: nowrap;
        }
        .col {
            flex: 1 0 auto;
        }

    </style>
</head>

<body>
<?php include 'header.php';?>
<main class="container">
    <h1 class="text-center">Browse By Genre</h1>
    <br>
    <h2><b><?php echo "Strategy Games:"; ?></b></h2>
    <div class="scroll-container">
        <div class="row row-cols-4 g-0" >
            <?php
            // get the games that match the search term
            $games = Game::searchGamesByGenre("Strategy Games", 10, $page);
            $games = array_slice($games, 0, 10);
            foreach ($games as $game) {
                echo "<div class='col' style='padding: 10px'>";
                echo $game->cardView();
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <br>
    <h2><b><?php echo "Party Games:"; ?></b></h2>
    <div class="scroll-container">
        <div class="row row-cols-4 g-0">
            <?php
            // get the games that match the search term
            $games = Game::searchGamesByGenre("Party Games", 10, $page);
            $games = array_slice($games, 0, 10);
            foreach ($games as $game) {
                echo "<div class='col' style='padding: 10px'>";
                echo $game->cardView();
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <br>
    <h2><b><?php echo "Family Games:"; ?></b></h2>
    <div class="scroll-container">
        <div class="row row-cols-4 g-0">
            <?php
            // get the games that match the search term
            $games = Game::searchGamesByGenre("Family Games", 10, $page);
            $games = array_slice($games, 0, 10);
            foreach ($games as $game) {
                echo "<div class='col' style='padding: 10px'>";
                echo $game->cardView();
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <br>
    <h2><b><?php echo "Customizable Games:"; ?></b></h2>
    <div class="scroll-container">
        <div class="row row-cols-4 g-0">
            <?php
            // get the games that match the search term
            $games = Game::searchGamesByGenre("Customizable Games", 10, $page);
            $games = array_slice($games, 0, 10);
            foreach ($games as $game) {
                echo "<div class='col' style='padding: 10px'>";
                echo $game->cardView();
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <br>
    <h2><b><?php echo "Thematic Games:"; ?></b></h2>
    <div class="scroll-container">
        <div class="row row-cols-4 g-0">
            <?php
            // get the games that match the search term
            $games = Game::searchGamesByGenre("Thematic Games", 10, $page);
            $games = array_slice($games, 0, 10);
            foreach ($games as $game) {
                echo "<div class='col' style='padding: 10px'>";
                echo $game->cardView();
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <br>
    <h2><b><?php echo "Abstract Games:"; ?></b></h2>
    <div class="scroll-container">
        <div class="row row-cols-4 g-0">
            <?php
            // get the games that match the search term
            $games = Game::searchGamesByGenre("Abstract Games", 10, $page);
            $games = array_slice($games, 0, 10);
            foreach ($games as $game) {
                echo "<div class='col' style='padding: 10px'>";
                echo $game->cardView();
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <br>
    <h2><b><?php echo "Wargames:"; ?></b></h2>
    <div class="scroll-container">
        <div class="row row-cols-4 g-0">
            <?php
            // get the games that match the search term
            $games = Game::searchGamesByGenre("Wargames", 10, $page);
            $games = array_slice($games, 0, 10);
            foreach ($games as $game) {
                echo "<div class='col' style='padding: 10px'>";
                echo $game->cardView();
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <br>
    <h2><b><?php echo "Children's Games:"; ?></b></h2>
    <div class="scroll-container">
        <div class="row row-cols-4 g-0">
            <?php
            // get the games that match the search term
            $games = Game::searchGamesByGenre("Children's Games", 10, $page);
            $games = array_slice($games, 0, 10);
            foreach ($games as $game) {
                echo "<div class='col' style='padding: 10px'>";
                echo $game->cardView();
                echo "</div>";
            }
            ?>
        </div>
    </div>

</main>
<?php include 'footer.php';?>
</body>
</html>