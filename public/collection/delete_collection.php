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

    if (!isset($collectionId) || $collectionId === '') {
        header("Location: index.php");
    }

    try {
        $result = Tabletop\Entities\Collection::deleteCollection($collectionId);
        $_SESSION['success'] = "Collection deleted successfully";
        header("Location: ../dashboard.php");
        exit;
    } catch (\Exception $e) {
        $_SESSION['error'] = "Failed to delete collection";
        header("Location: index.php?collection_id=" . $collectionId);
        exit;
    }
} else {
    header("Location: index.php");
}