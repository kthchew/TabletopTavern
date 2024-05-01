#!/usr/local/bin/php
<?php
require '../../vendor/autoload.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $collectionName = isset($_POST['collection-name']) ? htmlspecialchars($_POST['collection-name']) : null;

    try {
        $collection = Tabletop\Entities\Collection::createCollection($collectionName);
        header("Location: index.php?collection_id=" . $collection);
        exit;
    } catch (Exception $e) {
        if ($e->getMessage() == "Collection already exists") {
            $_SESSION['error'] = $e->getMessage();
        }
        else {
            $_SESSION['error'] = "Failed to create collection";
        }
        header("Location: ../dashboard.php");
        exit;
    }
}