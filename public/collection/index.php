#!/usr/local/bin/php
<?php 
require '../../vendor/autoload.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

$collection_id = htmlspecialchars($_GET['collection_id']) ?? null;
if (!isset($collection_id)) {
    $error = "Collection not found.";
} else {
    $collection = Tabletop\Entities\Collection::getUserCollection($collection_id);
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
<?php include '../header.php';?>

<main class="container">
    <?php if (!isset($collection) || !$collection): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php else: ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div id="error-collection-alert" class="alert alert-danger"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php elseif (isset($_SESSION['success'])): ?>
            <div id="success-collection-alert" class="alert alert-success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
    <div style="display: flex; justify-content: center;">
        <input type="hidden" name="original-name" value="<?= htmlspecialchars($collection->getName()) ?>">
        <h1 style="margin-right: 10px;"><?= $collection->getName() ?></h1>
        <div class="dropdown" style="display: inline-block; vertical-align: middle;">
            <button id="collection-options" type="button" data-bs-toggle="dropdown" class="btn btn-round p-3 rounded-circle dropdown-toggle" aria-expanded="false">···</button>
            <ul class="dropdown-menu" aria-labelledby="collection-options">
                <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#edit-modal" style = "color: #1C5E33">Edit name</button></li>
                <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#delete-modal" style = "color: #1C5E33">Delete</button></li>
            </ul>
        </div>
    </div>
        <?php if (count(Tabletop\Entities\Game::getCollectionGames($collection_id)) == 0): ?>
            <p align="center" style="font-size: 20px">Add a game to your collection by browsing or searching!</p>
        <?php else: ?>
        <br>
        <div class="row row-cols-4 mb-4">

            <?php
            // get the games for collection
            $games = Tabletop\Entities\Game::getCollectionGames($collection_id);
            $games = array_slice($games, 0, 12);
            foreach ($games as $game) {
                echo "<div class='col'>";
                echo $game->connectionCardView();
                echo "</div>";
            }
            ?>

        </div>
        <?php endif; ?>
    <?php endif; ?>
</main>

<?php include 'modal.php';?>
<?php include '../footer.php';?>

<script>
    // Fade out error and success alerts after 3 seconds
    $(document).ready(function(){
        $("#error-collection-alert").delay(3000).fadeOut();
        $("#success-collection-alert").delay(3000).fadeOut();
    });
</script>
</body>

</html>
