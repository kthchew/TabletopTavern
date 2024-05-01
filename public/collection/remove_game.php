#!/usr/local/bin/php
<?php
require '../../vendor/autoload.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gameId = isset($_POST['game-id']) ? htmlspecialchars($_POST['game-id']) : null;
    $collectionId = isset($_POST['collection-id']) ? htmlspecialchars($_POST['collection-id']) : null;

    if (!isset($collectionId) || $collectionId === '') {
        header("Location: index.php");
    } else if (!isset($gameId) || $gameId === '') {
        $_SESSION['error'] = "Failed to remove game from collection";
        header("Location: index.php?collection_id=" . $collectionId);
    }

    try {
        $result = Tabletop\Entities\Collection::removeGameFromCollection($collectionId, $gameId);
        $_SESSION['success'] = "Game removed successfully";
        header("Location: index.php?collection_id=" . $collectionId);
        exit;
    } catch (\Exception $e) {
        $_SESSION['error'] = "Failed to remove game from collection";
        header("Location: index.php?collection_id=" . $collectionId);
        exit;
    }
} else {
    $_SESSION['error'] = "Failed to add game to collection";
    $collectionId = isset($_POST['collection-id']) ? htmlspecialchars($_POST['collection-id']) : null;
    header("Location: index.php?collection_id=" . $collectionId);
    exit;
}