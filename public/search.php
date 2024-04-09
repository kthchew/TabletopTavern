<?php
require '../vendor/autoload.php';
// get the search term from the form
$searchTerm = $_POST['searchTerm'];
// include the Database class
//require_once '../src/Database.php';
// include the Game class
require_once '../src/entities/Game.php';
// get the games that match the search term
$games = Tabletop\Entities\Game::searchGamesByName($searchTerm);
foreach ($games as $game) {
    echo "<h3><a href='game/info.php?game_id={$game->getId()}'>{$game->getName()}</a></h3>";
    echo "<p>Players: {$game->getMinPlayers()} - {$game->getMaxPlayers()}</p>";
    echo "<p>Play Time: {$game->getPlayTime()} minutes</p>";
    echo "<p>Minimum Age: {$game->getMinAge()}</p>";
    echo "<p>Rating: {$game->getRatingAverage()} ({$game->getRatingCount()} votes)</p>";
    echo "<p>Year Published: {$game->getYearPublished()}</p>";
    echo "<hr>";
}
