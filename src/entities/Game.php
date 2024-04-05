<?php

namespace Tabletop\Entities;

use Tabletop\Database;

class Game
{
    private $id;
    public $name;
    private $minPlayers;
    private $maxPlayers;
    private $playTime;
    private $minAge;
    private $ratingCount;
    private $ratingAverage;
    private $yearPublished;

    static function getGamesFromDatabase() {
        $db = Database::getInstance();
        $sql = "SELECT * FROM Games";
        $result = $db->query($sql);
        $games = [];
        while ($row = $result->fetch_assoc()) {
            $game = new Game();
            $game->id = $row['id'];
            $game->name = $row['name'];
            $game->minPlayers = $row['min_players'];
            $game->maxPlayers = $row['max_players'];
            $game->playTime = $row['play_time'];
            $game->minAge = $row['min_age'];
            $game->ratingCount = $row['rating_count'];
            $game->ratingAverage = $row['rating_average'];
            $game->yearPublished = $row['year_published'];
            $games[] = $game;
        }
        return $games;
    }
}