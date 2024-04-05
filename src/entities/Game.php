<?php

namespace Tabletop\Entities;

use Tabletop\Database;

class Game
{
    private $id;
    private $name;
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

    static function searchGamesByName($name) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM Games WHERE name LIKE ?");
        $str = "%" . $name . "%";
        $stmt->bind_param("s", $str);
        $stmt->execute();
        $result = $stmt->get_result();
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

    public function getId() {
        return $this->id;
    }
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getMinPlayers()
    {
        return $this->minPlayers;
    }

    /**
     * @return mixed
     */
    public function getMaxPlayers()
    {
        return $this->maxPlayers;
    }

    /**
     * @return mixed
     */
    public function getPlayTime()
    {
        return $this->playTime;
    }

    /**
     * @return mixed
     */
    public function getMinAge()
    {
        return $this->minAge;
    }

    /**
     * @return mixed
     */
    public function getRatingCount()
    {
        return $this->ratingCount;
    }

    /**
     * @return mixed
     */
    public function getRatingAverage()
    {
        return $this->ratingAverage;
    }

    /**
     * @return mixed
     */
    public function getYearPublished()
    {
        return $this->yearPublished;
    }
}