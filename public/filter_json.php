#!/usr/local/bin/php
<?php
session_start();
require '../vendor/autoload.php';
use Tabletop\Entities\Game;

//Receive the form data and call the searchGamesByMultipleParameters function from the Game class
if (isset($_GET['searchName']) || isset($_GET['searchGenre']) || isset($_GET['minPlayers']) || isset($_GET['maxPlayers']) || isset($_GET['playTime']) || isset($_GET['minAge'])) {
    $searchName = $_GET['searchName'];
    $searchGenre = $_GET['searchGenre'];
    $playerCount = $_GET['playerCount'];
    $playTime = $_GET['playTime'];
    $minAge = $_GET['minAge'];
    $pageSize = 12;
    $currentPage = $_GET['page'] ?? 1;

    $games = Game::searchGameByMultipleParameters($searchName, $searchGenre, $playerCount, $playTime, $minAge, $pageSize, $currentPage);
    $gameCards = [];
    foreach ($games as $game) {
        $gameCards[] = $game->cardView();
    }
    echo json_encode($gameCards);
}
