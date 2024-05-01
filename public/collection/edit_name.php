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
        $newName = htmlspecialchars($_POST['new-name']);
        $result = Tabletop\Entities\Collection::editCollectionName($collectionId, $newName);
        $_SESSION['success'] = "Collection name edited successfully";
        header("Location: index.php?collection_id=" . $collectionId);
        exit;
    } catch (\Exception $e) {
        if ($e->getMessage() == "Collection already exists") {
            $_SESSION['error'] = $e->getMessage();
        } else {
            $_SESSION['error'] = "Failed to edit collection name";
        }
        header("Location: index.php?collection_id=" . $collectionId);
        exit;
    }
} else {
    header("Location: index.php");
 }