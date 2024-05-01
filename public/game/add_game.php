#!/usr/local/bin/php
<?php
require '../../vendor/autoload.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $collectionId = htmlspecialchars($_GET['collection_id']) ?? null;
    $gameId = htmlspecialchars($_GET['game_id']) ?? null;

    if (!isset($gameId) || $gameId === '') {
        header("Location: info.php");
    } else if (!isset($collectionId) || $collectionId === '') {
        $_SESSION['error'] = "Failed to add game to collection";
        header("Location: info.php?game_id=" . $gameId);
    }

    try {
        $result = Tabletop\Entities\Collection::addGameToCollection($collectionId, $gameId);
        $_SESSION['success'] = "Game added successfully";
        header("Location: info.php?game_id=" . $gameId);
        exit;
    } catch (\Exception $e) {
        $_SESSION['error'] = "Failed to add game to collection";
        header("Location: info.php?game_id=" . $gameId);
        exit;
    }
} else {
    $_SESSION['error'] = "Failed to add game to collection";
    $gameId = $_GET['game_id'] ?? null;
    header("Location: info.php?game_id=" . $gameId);
    exit;
}