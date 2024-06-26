<?php

namespace Tabletop\Entities;

use Tabletop\Database;

class Collection
{
    private int $id;
    private string $name;

    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    private static function makeCollectionFromDBRow($row) {
        return new Collection($row['id'], $row['name']);
    }

    // checks if collection exists and if user owns it
    static function getUserCollection($collectionId): ?Collection {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM collections WHERE id = ? 
            AND id IN (SELECT collection_id FROM usercollectionconnection WHERE user_id = ?)");
        $stmt->bind_param("ii", $collectionId, $_SESSION['user']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
            return self::makeCollectionFromDBRow($row);
        } else {
            return null;
        }
    }

    static function getFavoritesId(): int {
        $db = Database::getInstance();
        $name = 'Favorites';
        $stmt = $db->prepare("SELECT id FROM collections WHERE name = ?
            AND id IN (SELECT collection_id FROM usercollectionconnection WHERE user_id = ?)");
        $stmt->bind_param("si", $name, $_SESSION['user']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $favoritesId = $row['id'];
        } else {
            $favoritesId = self::createCollection("Favorites");
        }

        return $favoritesId;
    }

    static function getFavoritesGames(): array {
        $favoritesId = self::getFavoritesId();
        return Game::getCollectionGames($favoritesId);
    }

    static function getUserCollections(): array {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM collections 
         WHERE id IN (SELECT collection_id FROM usercollectionconnection WHERE user_id = ?) 
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
        $db->begin_transaction();

        $normalized_name = strtolower($name);

        // check if user has collection with same name already
        $stmt = $db->prepare("SELECT * FROM collections WHERE LOWER(name) = ? 
                            AND id IN (SELECT collection_id FROM usercollectionconnection WHERE user_id = ?)");
        $stmt->bind_param("si", $normalized_name, $_SESSION['user']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            throw new \Exception("Collection already exists");
        }

        // add to collections
        $stmt = $db->prepare("INSERT INTO collections (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        if (!$stmt->execute()) {
            $db->rollback();
            throw new \Exception();
        }

        $collection = new Collection($stmt->insert_id, $name);

        // connect collection to user
        $stmt = $db->prepare("INSERT INTO usercollectionconnection (user_id, collection_id) VALUES (?, ?)");
        $stmt->bind_param("ii",$_SESSION['user'], $collection->id);
        $stmt->execute();
        if (!$stmt->execute()) {
            $db->rollback();
            throw new \Exception();
        }

        $db->commit();

        return $collection->id;
    }

    static function deleteCollection($collectionId): bool {
        $db = Database::getInstance();
        $db->begin_transaction();

        $stmt = $db->prepare("DELETE FROM usercollectionconnection WHERE user_id = ? AND collection_id = ?");
        $stmt->bind_param("ii",$_SESSION['user'], $collectionId);
        $stmt->execute();
        if (!$stmt->execute()) {
            $db->rollback();
            throw new \Exception();
        }

        $stmt = $db->prepare("DELETE FROM collectiongameconnection WHERE collection_id = ?");
        $stmt->bind_param("i", $collectionId);
        $stmt->execute();
        if (!$stmt->execute()) {
            $db->rollback();
            throw new \Exception();
        }

        $stmt = $db->prepare("DELETE FROM collections WHERE id = ?");
        $stmt->bind_param("i", $collectionId);
        $stmt->execute();
        if (!$stmt->execute()) {
            $db->rollback();
            throw new \Exception();
        }

        $db->commit();

        return true;
    }

    static function editCollectionName($collectionId, $newName): bool
    {
        $db = Database::getInstance();
        $normalized_new_name = strtolower($newName);

        // 1. Find if collection has same name
        // 2. Exclude collections with different capitalization of old name, so user can change
        // current collection's capitalization
        $stmt = $db->prepare("SELECT * FROM collections WHERE LOWER(name) = ? 
                            AND id IN (SELECT collection_id FROM usercollectionconnection WHERE user_id = ?)
                            AND id <> ?");
        $stmt->bind_param("sii", $normalized_new_name, $_SESSION['user'], $collectionId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            throw new \Exception("Collection already exists");
        }

        $stmt = $db->prepare("UPDATE collections SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $newName, $collectionId);
        $stmt->execute();
        if ($stmt->affected_rows != 1) {
            throw new \Exception();
        }

        return true;
    }

    static function addGameToCollection($collectionId, $gameId): bool {
        $db = Database::getInstance();

        $stmt = $db->prepare("SELECT * FROM collectiongameconnection WHERE collection_id = ? AND game_id = ?");
        $stmt->bind_param("ii",$collectionId, $gameId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            throw new \Exception("Game already exists in collection");
        }
        // connect game to collection
        $stmt = $db->prepare("INSERT INTO collectiongameconnection (collection_id, game_id) VALUES (?, ?)");
        $stmt->bind_param("ii",$collectionId, $gameId);
        $stmt->execute();
        if ($stmt->affected_rows != 1) {
            throw new \Exception();
        }

        return true;
    }

    static function removeGameFromCollection($collectionId, $gameId): bool {
        $db = Database::getInstance();

        $stmt = $db->prepare("DELETE FROM collectiongameconnection WHERE collection_id = ? AND game_id = ?");
        $stmt->bind_param("ii",$collectionId, $gameId);
        $stmt->execute();
        if ($stmt->affected_rows != 1) {
            throw new \Exception();
        }

        return true;
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