#!/usr/local/bin/php
<?php 
require '../../vendor/autoload.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

$collection_id = $_GET['collection_id'] ?? null;
if (!isset($collection_id)) {
    $error = "Collection not found.";
} else {
    $userOwnsCollection = Tabletop\Entities\Collection::doesUserCollectionExistById($collection_id);
    if (!$userOwnsCollection) {
        $error = "Collection not found.";
    }
    else {
        $collection = Tabletop\Entities\Collection::getCollectionById($collection_id);
        if (!$collection) {
            $error = "Collection not found.";
        }
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

    <script>
        $(document).ready(function() {

        });
    </script>
</head>

<body>
<?php include '../header.php';?>

<main class="container">
    <?php if (!isset($collection)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php else: ?>
    <div style="display: flex; justify-content: center;">
        <h1 style="margin-right: 10px;"><?= $collection->getName() ?></h1>
        <div class="dropdown" style="display: inline-block; vertical-align: middle;">
            <button id="collection-options" type="button" data-bs-toggle="dropdown" class="btn btn-round p-3 rounded-circle dropdown-toggle" aria-expanded="false">···</button>
            <ul class="dropdown-menu" aria-labelledby="collection-options">
                <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#edit-modal" style = "color: #1C5E33">Edit name</button></li>
                <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#delete-modal" style = "color: #1C5E33">Delete</button></li>
            </ul>
        </div>
    </div>
    <p align="center" style="font-size: 20px">Add a game to your collection by browsing or searching!</p>
    <?php endif; ?>
</main>

<div class="modal" id="edit-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit collection name</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="dashboard.php" method="post">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                    <script>
                        $(document).ready(function(){
                            $('#edit-modal').modal('show'); // Show the modal if there's an error
                        });
                    </script>
                <?php endif; ?>
                <div class="modal-body">
                    <input type="text" name="new-collection-name" id="new-collection-name" class="form-control" value="">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn" id="edit-btn" disabled>Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../footer.php';?>
</body>

</html>
