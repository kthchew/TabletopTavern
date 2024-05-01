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
    if ($collectionId == Tabletop\Entities\Collection::getFavoritesId()) {
        $destination = "Location: $rootPath/dashboard.php";
    } else {
        $destination = "Location: $rootPath/collection/index.php?collection_id=" . $collectionId;
    }

    if (!isset($collectionId) || $collectionId === '') {
        header("Location: $rootPath/collection/index.php");
    } else if (!isset($gameId) || $gameId === '') {
        $_SESSION['error'] = "Failed to remove game from collection";
        header($destination);
        exit;
    }

    try {
        $result = Tabletop\Entities\Collection::removeGameFromCollection($collectionId, $gameId);
        $_SESSION['success'] = "Game removed successfully";
    } catch (\Exception $e) {
        $_SESSION['error'] = "Failed to remove game from collection";
    }
    header($destination);
    exit;

} else {
    $_SESSION['error'] = "Failed to add game to collection";
    $collectionId = isset($_POST['collection-id']) ? htmlspecialchars($_POST['collection-id']) : null;
    if ($collectionId == Tabletop\Entities\Collection::getFavoritesId()) {
        $destination = "Location: $rootPath/dashboard.php";
    } else {
        $destination = "Location: $rootPath/collection/index.php?collection_id=" . $collectionId;
    }
    header($destination);
    exit;
}