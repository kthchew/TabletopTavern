#!/usr/local/bin/php
<?php
require '../vendor/autoload.php';
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $collectionName = htmlspecialchars($_POST["collection-name"]);
    try {
        $collection = Tabletop\Entities\Collection::createCollection($collectionName);
        header("Location: collection/index.php?collection_id=" . $collection);
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
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
</head>

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

<body>
<?php include 'header.php';?>
<main class="container">
    <?php if (isset($_SESSION['success'])): ?>
        <div id="success-dashboard-alert" class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <h1 class = "text-center"><?php echo $_SESSION['username'] ?>'s Dashboard</h1>
    <h2>Favorites</h2>

    <div style="display: flex;">
        <h2 style="margin-right: 10px;">Collections</h2>
        <button class="btn square-btn" type="button" data-bs-toggle="modal" data-bs-target="#collection-modal">&plus;</button>
    </div>
    <div class="row row-cols-4 mb-4">

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
            <form action="dashboard.php" method="post">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger mx-3 mt-3"><?= $error ?></div>
                    <script>
                        $(document).ready(function(){
                            $('#collection-modal').modal('show'); // Show the modal if there's an error
                        });
                    </script>
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
</script>
</body>

</html>