#!/usr/local/bin/php
<?php
require '../vendor/autoload.php';
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

use Tabletop\Entities\Collection;
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

    <link rel="stylesheet" href="css/style.css">

    <style>
        .scroll-container {
            overflow: auto;
            background-color: #DEEDC8;
            border-radius: 10px;
            overflow-x: scroll;
        }

        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #cee1b1;
        }

        ::-webkit-scrollbar-thumb {
            background: #a2d15c;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #629716;
        }

    </style>

    <script>
        // disable button when there's no input
        $(document).ready(function(){
            $("#collection-name").keyup(function(){
                if ($("#collection-name").val().trim().length === 0) {
                    $("#create-btn").prop("disabled", true);
                } else {
                    $("#create-btn").prop("disabled", false);
                }
            });
        });
    </script>
</head>

<body>
<?php include 'header.php';?>
<main style="padding-left: 80px; padding-right: 80px;">
    <?php if (isset($_SESSION['success'])): ?>
        <div id="success-dashboard-alert" class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <br>
    <h1 class = "text-center"><?php echo $_SESSION['username'] ?>'s Dashboard</h1>
    <br>
    <hr>
    <h2>Favorites</h2>
    <br>
    <div class="scroll-container">
        <div class="row col-lg-3 col-md-4 col-sm-12 g-0 flex-nowrap">
            <?php
            $favGames = Collection::getFavoritesGames();
            foreach ($favGames as $game) {
                echo "<div class='col' style='padding: 10px; flex: 1 0 auto;'>";
                echo $game->collectionCardView(Collection::getFavoritesId());
                echo "</div>";
            }
            ?>
        </div>
    </div>
    <br>
    <div style="display: flex;">
        <h2 style="margin-right: 10px;">Collections</h2>
        <button class="btn btn-square" type="button" data-bs-toggle="modal" data-bs-target="#collection-modal">&plus;</button>
    </div>
    <div class="row row-cols-md-4 row-cols-sm-2 row-cols-1 mb-4 game-card-container">

    <?php
        $collections = Collection::getUserCollections();
        foreach ($collections as $collection) {
            echo "<div class='col'>";
            echo $collection->cardView();
            echo "</div>";
        }
        ?>

    </div>
</main>

<div class="modal" id="collection-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create a new collection</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="collection/create_collection.php" method="post">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger mx-3 mt-3" id="modal-alert"><?= $_SESSION['error'] ?></div>
                    <script>
                        $(document).ready(function(){
                            $('#collection-modal').modal('show'); // Show the modal if there's an error
                        });
                    </script>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                <div class="modal-body">
                    <input type="text" name="collection-name" id="collection-name" class="form-control" placeholder="Name your collection">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn" id="create-btn" disabled>Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'footer.php';?>

<script>
    $("#success-dashboard-alert").delay(3000).fadeOut();
    $("#modal-alert").delay(3000).fadeOut();
</script>
</body>

</html>