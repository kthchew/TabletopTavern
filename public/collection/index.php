#!/usr/local/bin/php
<?php 
require '../../vendor/autoload.php';
session_start();
$collection_id = isset($_GET['collection_id']) ? $_GET['collection_id'] : null;
if (!isset($collection_id)) {
    $error = "Collection not found.";
} else {
    $collection = Tabletop\Entities\Collection::getCollectionById($collection_id);
    if (!$collection) {
        $error = "Collection not found.";
    }
}
define('__HEADER_FOOTER_PHP__', true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tabletop Tavern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
<?php include '../header.php';?>

<main class="container">
    <?php if (!isset($collection)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php else: ?>
    <h1 align="center"><?= $collection->getName() ?></h1>
    <p align="center" style="font-size: 20px">Browse for a game to add, or search for one!</p>
    <br><br><br>
    <?php endif; ?>
</main>

<?php include '../footer.php';?>
</body>

</html>
