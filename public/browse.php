#!/usr/local/bin/php
<?php
require '../vendor/autoload.php';
session_start();
$page = $_GET['page'] ?? 1;
use Tabletop\Entities\Game;
define('__HEADER_FOOTER_PHP__', true);


$gameCards = '';

//Receive the form data and call the searchGamesByMultipleParameters function from the Game class
if (isset($_POST['searchName']) || isset($_POST['searchGenre']) || isset($_POST['minPlayers']) || isset($_POST['maxPlayers']) || isset($_POST['playTime']) || isset($_POST['minAge'])) {
    $searchName = $_POST['searchName'];
    $searchGenre = $_POST['searchGenre'];
    $playerCount = $_POST['playerCount'];
    $playTime = $_POST['playTime'];
    $minAge = $_POST['minAge'];
    $pageSize = 10;
    $currentPage = 1;

//var_dump($searchName, $searchGenre, $minPlayers, $maxPlayers, $playTime, $minAge, $pageSize, $currentPage);

    $games = Game::searchGameByMultipleParameters($searchName, $searchGenre, $playerCount, $playTime, $minAge, $pageSize, $currentPage);

// If there are no games found, display a message
    if (empty($games)) {
        $gameCards = "<div class=\"d-flex justify-content-center align-items-center\">No games found that meet these specifications. Please try to change or broaden your specifications.</div>";
    }

    foreach ($games as $game) {
        $gameCards .= $game->cardView();
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

<br>
<h1 class="text-center">Browse By Genre</h1>
<br>

<?php $genres = Game::getAllGenres()?>
<div class="row justify-content-center">
    <form action="filter.php" method="post" class="col-md-9">
        <input type="text" name="searchName" placeholder="Search by Name..." value="<?php echo isset($searchName) ? $searchName : ''?>">
        <input list="genre" name="searchGenre" placeholder="Search by Genre..." value="<?php echo isset($searchGenre) ? $searchGenre : ''?>">
        <datalist id="genre">
            <?php foreach ($genres as $genre): ?>
            <option value="<?php echo $genre; ?>">
                <?php endforeach; ?>
        </datalist>
        <!--<div>
        <select name="searchGenre" id="searchGenre" class="form-select">
            <option value="">Search by Genre...</option>
            <?php foreach ($genres as $genre): ?>
                <option value="<?php echo $genre; ?>" <?php if (isset($searchGenre) && $searchGenre == $genre) echo 'selected'; ?>><?php echo $genre; ?></option>
            <?php endforeach; ?>
        </select>
    </div>-->

        <!--<input type="number" name="minPlayers" placeholder="Minimum Players..." value="<?php echo isset($minPlayers) ? $minPlayers : ''?>">
    <input type="number" name="maxPlayers" placeholder="Maximum Players..." value="<?php echo isset($maxPlayers) ? $maxPlayers : ''?>">-->
        <input type="number" name="playerCount" placeholder="Number of Players..." value="<?php echo isset($playerCount) ? $playerCount : ''?>">
        <input type="number" name="playTime" placeholder="Max Play Time (Minutes)..." value="<?php echo isset($playTime) ? $playTime : ''?>">
        <input type="number" name="minAge" placeholder="Minimum Age..." value="<?php echo isset($minAge) ? $minAge : ''?>">
        <input type="submit" value="Search">
    </form>
</div>

<div class="row row-cols-4 mb-4">
    <?php echo $gameCards; ?>
</div>

<main class="container" style = "padding-left: 80px; padding-right: 80px; ">
    <br>
    <h2><b><?php echo "Strategy Games:"; ?></b></h2>
    <div class="scroll-container">
        <div class="row row-cols-lg-4 row-cols-sm-2 row-cols-1 g-0" >
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

    <h2><b><?php echo "Party Games:"; ?></b></h2>
    <div class="scroll-container">
        <div class="row row-cols-lg-4 row-cols-sm-2 row-cols-1 g-0">
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
        <div class="row row-cols-lg-4 row-cols-sm-2 row-cols-1 g-0">
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
        <div class="row row-cols-lg-4 row-cols-sm-2 row-cols-1 g-0">
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
        <div class="row row-cols-lg-4 row-cols-sm-2 row-cols-1 g-0">
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
        <div class="row row-cols-lg-4 row-cols-sm-2 row-cols-1 g-0">
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
        <div class="row row-cols-lg-4 row-cols-sm-2 row-cols-1 g-0">
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
        <div class="row row-cols-lg-4 row-cols-sm-2 row-cols-1 g-0">
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