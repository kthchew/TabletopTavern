<?php

namespace Tabletop\Entities;

use Tabletop\Database;

class Game
{
    private int $id;
    private string $name;
    private int $minPlayers;
    private int $maxPlayers;
    private int $playTime;
    private int $minAge;
    private int $ratingCount;
    private float $ratingAverage;
    private int $yearPublished;
    private array $mechanics;
    private array $subgenres;
    private array $ratings;
    private ?string $description;
    private ?string $imageURL;
    private ?string $thumbnailURL;
    private ?string $apiResponse = null;

    private static function makeGameFromDBRow($row)
    {
        $db = Database::getInstance();
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
        // lookup mechanics as a many-to-many relationship from the SQL db, and store them in the game object
        $stmt = $db->prepare("SELECT mechanic FROM Mechanics JOIN GameMechanicConnection ON Mechanics.id = GameMechanicConnection.mechanic_id WHERE GameMechanicConnection.game_id = ?");
        $stmt->bind_param("i", $row['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $game->mechanics = array_merge(...$result->fetch_all());

        $stmt = $db->prepare("SELECT subgenre FROM Subgenre JOIN GameSubgenreConnection ON Subgenre.id = GameSubgenreConnection.subgenre_id WHERE GameSubgenreConnection.game_id = ?");
        $stmt->bind_param("i", $row['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $game->subgenres = array_merge(...$result->fetch_all());

        $stmt = $db->prepare("SELECT * FROM Ratings WHERE game_id = ?");
        $stmt->bind_param("i", $row['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $game->ratings = $result->fetch_all(MYSQLI_ASSOC);

        return $game;
    }

    static function getGamesFromDatabase(): array
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM Games";
        $result = $db->query($sql);
        $games = [];
        while ($row = $result->fetch_assoc()) {
            $games[] = self::makeGameFromDBRow($row);
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
            $games[] = self::makeGameFromDBRow($row);
        }
        return $games;
    }

    static function searchGamesByGenre($subgenre, $pageSize, $currentPage): array
    {
        $db = Database::getInstance();
        // search games by genre
        $str = "%" . $subgenre . "%";
        $stmt = $db->prepare("SELECT * FROM Games JOIN GameSubgenreConnection ON Games.id = GameSubgenreConnection.game_id JOIN Subgenre ON GameSubgenreConnection.subgenre_id = Subgenre.id WHERE subgenre LIKE ? LIMIT ? OFFSET ?");
        $offset = ($currentPage - 1) * $pageSize;
        $stmt->bind_param("sii", $str, $pageSize, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $games = [];
        while ($row = $result->fetch_assoc()) {
            //ensures the id corresponds with the game id and not the subgenre id
            //fixes issue of repeated descriptions and missing info in browse cards
            $row['id'] = $row['game_id'];
            $games[] = self::makeGameFromDBRow($row);
        }
        return $games;
    }

    static function searchGameByMultipleParameters($name, $minPlayers, $maxPlayers, $playTime, $minAge, $pageSize, $currentPage): array
    {
        $db = Database::getInstance();

        // Initialize the query and the parameters array
        $query = "SELECT * FROM Games WHERE ";
        $params = [];
        $types = "";

        // Add conditions to the query based on the parameters that are not empty
        if (!empty($name)) {
            $query .= "name LIKE ? AND ";
            $params[] = "%" . $name . "%";
            $types .= "s";
        }
        if (!empty($minPlayers)) {
            $query .= "min_players >= ? AND ";
            $params[] = $minPlayers;
            $types .= "i";
        }
        if (!empty($maxPlayers)) {
            $query .= "max_players <= ? AND ";
            $params[] = $maxPlayers;
            $types .= "i";
        }
        if (!empty($playTime)) {
            $query .= "play_time <= ? AND ";
            $params[] = $playTime;
            $types .= "i";
        }
        if (!empty($minAge)) {
            $query .= "min_age <= ? AND ";
            $params[] = $minAge;
            $types .= "i";
        }

        // Remove the last "AND "
        $query = substr($query, 0, -4);

        // Add the order by, limit and offset clauses
        $query .= " ORDER BY name LIMIT ? OFFSET ?";
        $params[] = $pageSize;
        $params[] = ($currentPage - 1) * $pageSize;
        $types .= "ii";

        // Prepare and execute the statement
        $stmt = $db->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch the games
        $games = [];
        while ($row = $result->fetch_assoc()) {
            $games[] = self::makeGameFromDBRow($row);
        }
        return $games;
    }


    static function getGameById($id): ?Game
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM Games WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
            return self::makeGameFromDBRow($row);
        } else {
            return null;
        }
    }

    static function getIDfromName($name): ?string{
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT id FROM Games WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
            return $row['id'];
        }else {
            return null;
        }
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMinPlayers(): int
    {
        return $this->minPlayers;
    }

    public function getMaxPlayers(): int
    {
        return $this->maxPlayers;
    }

    /**
     * @return int Play time in minutes.
     */
    public function getPlayTime(): int
    {
        return $this->playTime;
    }

    public function getMinAge(): int
    {
        return $this->minAge;
    }

    public function getRatingCount(): int
    {
        return $this->ratingCount;
    }

    public function getRatingAverage(): float
    {
        return $this->ratingAverage;
    }

    public function getYearPublished(): int
    {
        return $this->yearPublished;
    }

    private function getAPIResponse(): void {
        $this->apiResponse = file_get_contents("https://boardgamegeek.com/xmlapi2/thing?id=$this->id");

        $this->description = str_replace("&#10;", "<br>", simplexml_load_string($this->apiResponse)->item->description);
        $this->imageURL = simplexml_load_string($this->apiResponse)->item->image;
        $this->thumbnailURL = simplexml_load_string($this->apiResponse)->item->image;
        // store them in db
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE Games SET description = ?, image_url = ?, thumbnail_url = ? WHERE id = ?");
        $stmt->bind_param("sssi", $this->description, $this->imageURL, $this->thumbnailURL, $this->id);
        $stmt->execute();
    }

    public function getDescription(): string {
        if ($this->description == null) {
            $this->getAPIResponse();
        }
        return $this->description;
    }

    public function getImageURL(): string {
        if ($this->imageURL == null) {
            $this->getAPIResponse();
        }
        return $this->imageURL;
    }

    public function getMechanics(): array
    {
        return $this->mechanics;
    }

    public function getSubgenres(): array
    {
        return $this->subgenres;
    }

    public function getRatings(): array
    {
        return $this->ratings;
    }

    public function getThumbnailURL(): string
    {
        return $this->thumbnailURL;
    }

    public function cardView(): string
    {
        $truncatedDescription = substr($this->getDescription(), 0, 100) . "...";
        $mechanics = implode(", ", $this->mechanics);
        $subgenres = implode(", ", $this->subgenres);
        return "
        <a class='text-decoration-none' href='game/info.php?game_id={$this->getId()}'>
            <div class='card my-2'>
                <div class='card-body'>
                    <h5 class='card-title'>{$this->name} ({$this->yearPublished})</h5>
                    <p class='card-text m-0'>{$this->minPlayers} - {$this->maxPlayers} players, {$this->playTime} minutes, {$this->minAge}+</p>
                    <small class='card-text fst-italic'>{$mechanics}</small>
                    <br>
                    <small class='card-text fst-italic'>{$subgenres}</small>
                    <p class='card-text'>{$truncatedDescription}</p>
                </div>
            </div>
        </a>";
    }

    //not used anymore!
    public function browseCard($game): string
    {
        $truncatedDescription = substr($game->getDescription(), 0, 100) . "...";
        $mechanics = implode(", ", $game->mechanics);
        $subgenres = implode(", ", $game->subgenres);
        return "
        <a class='text-decoration-none' href='game/info.php?game_id={$game->getIDfromName($game->name)}'>
            <div class='card my-2'>
                <div class='card-body'>
                    <h5 class='card-title'>{$game->name} ({$game->yearPublished})</h5>
                    <p class='card-text m-0'>{$game->minPlayers} - {$game->maxPlayers} players, {$game->playTime} minutes, {$game->minAge}+</p>
                    <small class='card-text fst-italic'>{$mechanics}</small>
                    <br>
                    <small class='card-text fst-italic'>{$subgenres}</small>
                    <p class='card-text'>{$truncatedDescription}</p>
                </div>
            </div>
        </a>";
    }

}