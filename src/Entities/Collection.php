<?php

namespace Tabletop\Entities;

use Tabletop\Database;

class Collection
{
    private static array $instances = array();
    private static bool $hasFavorites = false;

    private int $id;
    private string $name;

    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
        self::$instances[$id] = $this;
        if ($name === "Favorites") {
            $hasFavorites = true;
        }
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    private static function makeCollectionFromDBRow($row) {
        if (isset(self::$instances[$row['id']])) {
            return self::$instances[$row['id']];
        } else {
            return new Collection($row['id'], $row['name']);
        }

    }

    static function doesUserCollectionExistById($collectionId): bool {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM Collections WHERE id = ? 
            AND id IN (SELECT collection_id FROM UserCollectionConnection WHERE user_id = ?)");
        $stmt->bind_param("ii", $collectionId, $_SESSION['user']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            return False;
        }
        return True;
    }

    static function doesUserCollectionExistByName($collectionName): bool {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM Collections WHERE name = ? 
            AND id IN (SELECT collection_id FROM UserCollectionConnection WHERE user_id = ?)");
        $stmt->bind_param("si", $collectionName, $_SESSION['user']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            return False;
        }
        return True;
    }

    static function getUserCollections(): array {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM Collections 
         WHERE id IN (SELECT collection_id FROM UserCollectionConnection WHERE user_id = ?) 
           AND name <> 'Favorites'");
        $stmt->bind_param("i", $_SESSION['user']);
        $stmt->execute();
        $result = $stmt->get_result();
        $resultArr = [];
        while ($row = $result->fetch_assoc()) {
            $resultArr[] = self::makeCollectionFromDBRow($row);
        }
        return $resultArr;
    }

    static function createCollection($name): int {
        $db = Database::getInstance();

        $normalized_name = strtolower($name);

        // check if user has collection with same name already
        $stmt = $db->prepare("SELECT * FROM Collections WHERE LOWER(name) = ? 
                            AND id IN (SELECT collection_id FROM UserCollectionConnection WHERE user_id = ?)");
        $stmt->bind_param("si", $normalized_name, $_SESSION['user']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            throw new \Exception("Collection already exists");
        }

        // add to collections
        $stmt = $db->prepare("INSERT INTO Collections (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        if ($stmt->affected_rows != 1) {
            throw new \Exception("Failed to create collection");
        }

        $collection = new Collection($stmt->insert_id, $name);

        // connect collection to user
        $stmt = $db->prepare("INSERT INTO UserCollectionConnection (user_id, collection_id) VALUES (?, ?)");
        $stmt->bind_param("ii",$_SESSION['user'], $collection->id);
        $stmt->execute();

        return $collection->id;
    }

    public function deleteCollection() {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM Collections WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        if ($stmt->affected_rows != 1) {
            throw new \Exception("Failed to delete collection");
        }

        $stmt = $db->prepare("DELETE FROM UserCollectionConnection WHERE user_id = ? AND collection_id = ?");
        $stmt->bind_param("ii",$_SESSION['user'], $this->id);
        $stmt->execute();

        $stmt = $db->prepare("DELETE FROM CollectionGameConnection WHERE collection_id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
    }

    public function editCollectionName($name) {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE Collections SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $this->id);
        $stmt->execute();
        if ($stmt->affected_rows != 1) {
            throw new \Exception("Failed to edit collection name");
        }
    }

    public function addGameToCollection($gameId) {
        $db = Database::getInstance();
        // connect game to collection
        $stmt = $db->prepare("INSERT INTO CollectionGameConnection (collection_id, game_id) VALUES (?, ?)");
        $stmt->bind_param("ii",$this->id, $gameId);
        $stmt->execute();
        if ($stmt->affected_rows != 1) {
            throw new \Exception("Failed to add game to collection");
        }
    }

    public function removeGameFromCollection($gameId) {
        $db = Database::getInstance();

        $stmt = $db->prepare("DELETE FROM CollectionGameConnection WHERE collection_id = ? AND game_id = ?");
        $stmt->bind_param("ii",$this->id, $gameId);
        $stmt->execute();
        if ($stmt->affected_rows != 1) {
            throw new \Exception("Failed to remove game from connection");
        }
    }

    static function getCollectionById($id): ?Collection
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM Collections WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
            return self::makeCollectionFromDBRow($row);
        } else {
            return null;
        }
    }

    public function cardView() {
        return "
        <a class='text-decoration-none' href='collection/index.php?collection_id={$this->getId()}'>
            <div class='card my-2'>
                <div class='card-body'>
                    <h5 class='card-title'>{$this->name}</h5>
                </div>
            </div>
        </a>";
    }
}