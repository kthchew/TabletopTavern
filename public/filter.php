#!/usr/local/bin/php
<?php
session_start();
require '../vendor/autoload.php';
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

    $games = Game::searchGameByMultipleParameters( $searchName, $searchGenre, $playerCount, $playTime, $minAge, $pageSize, $currentPage);

    // If there are no games found, display a message
    if (empty($games)) {
        $gameCards = "<div style = 'margin: auto; text-align: center'>
                      <br>
                      <br>
                      <h5><b>No games found</b></h5>
                      <p>Please change or broaden your specifications</p>
                      </div>";
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
</head>

<body>
<?php include 'header.php';?>
<?php $genres = Game::getAllGenres()?>
<div class="row justify-content-center">
<form action="" method="post" class="col-md-9">
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

<?php include 'footer.php';?>
</body>
</html>