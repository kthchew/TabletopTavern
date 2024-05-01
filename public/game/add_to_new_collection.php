#!/usr/local/bin/php
<?php
require '../../vendor/autoload.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gameId = isset($_POST['game-id']) ? htmlspecialchars($_POST['game-id']) : null;
    $collectionName = isset($_POST['collection-name']) ? htmlspecialchars($_POST['collection-name']) : null;

    if (!isset($gameId) || $gameId === '') {
        header("Location: info.php");
    }

    try {
        $collectionId = Tabletop\Entities\Collection::createCollection($collectionName);
    } catch (Exception $e) {
        if ($e->getMessage() == "Collection already exists") {
            $_SESSION['duplicate-error'] = "Collection already exists";
        } else {
            $_SESSION['error'] = "Failed to create collection";
        }
        header("Location: info.php?game_id=" . $gameId);
        exit;
    }

    try {
        $result = Tabletop\Entities\Collection::addGameToCollection($collectionId, $gameId);
        $_SESSION['success'] = "Game added successfully";
        header("Location: info.php?game_id=" . $gameId);
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = "Failed to add game to collection";
        header("Location: info.php?game_id=" . $gameId);
        exit;
    }

} else {
    $_SESSION['error'] = "Failed to create collection";
    $gameId = $_GET['game_id'] ?? null;
    header("Location: info.php?game_id=" . $gameId);
    exit;
}