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
    private $description;
    private $imageURL;
    private $thumbnailURL;
    private $apiResponse = null;

    static function getGamesFromDatabase(): array
    {
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
            $game->description = $row['description'];
            $game->imageURL = $row['image_url'];
            $game->thumbnailURL = $row['thumbnail_url'];
            $games[] = $game;
        }
        return $games;
    }

    // currentPage
    static function searchGamesByName($name, $pageSize, $currentPage): array
    {
        $db = Database::getInstance();
        // search games by name and order by relevance
        $stmt = $db->prepare("SELECT * FROM Games WHERE name LIKE ? ORDER BY name LIMIT ? OFFSET ?");
        $str = "%" . $name . "%";
        $offset = ($currentPage - 1) * $pageSize;
        $stmt->bind_param("sii", $str, $pageSize, $offset);
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
            $game->description = $row['description'];
            $game->imageURL = $row['image_url'];
            $game->thumbnailURL = $row['thumbnail_url'];
            $games[] = $game;
        }
        return $games;
    }

    static function getGameById($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM Games WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
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
            $game->description = $row['description'];
            $game->imageURL = $row['image_url'];
            $game->thumbnailURL = $row['thumbnail_url'];
            return $game;
        } else {
            return null;
        }
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

    private function getAPIResponse() {
        $this->apiResponse = file_get_contents("https://boardgamegeek.com/xmlapi2/thing?id=$this->id");

        $this->description = str_replace("&#10;", "<br>", simplexml_load_string($this->apiResponse)->item->description);
        $this->imageURL = simplexml_load_string($this->apiResponse)->item->image;
        // store them in db
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE Games SET description = ?, image_url = ? WHERE id = ?");
        $stmt->bind_param("ssi", $this->description, $this->imageURL, $this->id);
        $stmt->execute();
    }

    public function getDescription() {
        if ($this->description == null) {
            $this->getAPIResponse();
        }
        return $this->description;
    }

    public function getImageURL() {
        if ($this->imageURL == null) {
            $this->getAPIResponse();
        }
        return $this->imageURL;
    }

    public function cardView()
    {
        $truncatedDescription = substr($this->getDescription(), 0, 100) . "...";
        return "<div class='card my-2'>
            <div class='card-body'>
                <h5 class='card-title'>{$this->name} ({$this->yearPublished})</h5>
                <p class='card-text'>{$this->minPlayers} - {$this->maxPlayers} players, {$this->playTime} minutes, {$this->minAge}+</p>
                <p class='card-text'>{$truncatedDescription}</p>
            </div>
        </div>";
    }
}