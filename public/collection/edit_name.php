#!/usr/local/bin/php
<?php
require '../../vendor/autoload.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $collectionId = $_GET['collection_id'] ?? null;

    if (!isset($collectionId) || $collectionId === '') {
        $error2 = "Collection not found";
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
            $_SESSION['error2'] = $e->getMessage();
        } else {
            $_SESSION['error2'] = "Failed to edit collection name";
        }
        header("Location: index.php?collection_id=" . $collectionId);
        exit;
    }
} else {
    $error2 = "Collection not found";
    header("Location: index.php");
 }