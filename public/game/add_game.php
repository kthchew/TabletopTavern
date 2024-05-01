#!/usr/local/bin/php
<?php
require '../../vendor/autoload.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

$rootPath = Tabletop\Config::getRootPath();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gameId = isset($_POST['game-id']) ? htmlspecialchars($_POST['game-id']) : null;
    $collectionId = isset($_POST['collection-id']) ? htmlspecialchars($_POST['collection-id']) : null;

    if (!isset($gameId) || $gameId === '') {
        header("Location: $rootPath/game/info.php");
    } else if (!isset($collectionId) || $collectionId === '') {
        $_SESSION['error'] = "Failed to add game to collection";
        header("Location: $rootPath/game/info.php?game_id=" . $gameId);
    }

    try {
        $result = Tabletop\Entities\Collection::addGameToCollection($collectionId, $gameId);
        $_SESSION['success'] = "Game added successfully";
        header("Location: $rootPath/game/info.php?game_id=" . $gameId);
        exit;
    } catch (\Exception $e) {
        if ($e->getMessage() == "Game already exists in collection") {
            $_SESSION['error'] = $e->getMessage();
        } else {
            $_SESSION['error'] = "Failed to add game to collection";
        }
        header("Location: $rootPath/game/info.php?game_id=" . $gameId);
        exit;
    }
} else {
    $_SESSION['error'] = "Failed to add game to collection";
    $gameId = $_GET['game_id'] ?? null;
    header("Location: $rootPath/game/info.php?game_id=" . $gameId);
    exit;
}