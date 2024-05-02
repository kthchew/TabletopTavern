#!/usr/local/bin/php
<?php
require '../vendor/autoload.php';
session_start();
// get the search term from the form
$searchTerm = isset($_GET['searchTerm']) ? $_GET['searchTerm'] : null;
$page = $_GET['page'] ?? 1;
use Tabletop\Entities\Game;
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

    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<?php include 'header.php';?>
<main style = "padding-left: 40px; padding-right: 40px; padding-bottom: 40px;">
<h1 class="text-center py-4">Search Results</h1>
    <p class="text-center"><b>Results for: </b><?php echo $searchTerm; ?></p>
    <p class="text-center"><b>Page: </b><?php echo $page; ?></p>
    <br>

    <div class="row row-cols-md-4 row-cols-sm-2 row-cols-1 mb-4">

            <?php
            // get the games that match the search term
            $games = Game::searchGamesByName($searchTerm, 12, $page);
            $games = array_slice($games, 0, 12);
            foreach ($games as $game) {
                echo "<div class='col'>";
                echo $game->cardView();
                echo "</div>";
            }
            ?>

    </div>
    <div class="d-flex justify-content-center">
        <nav>
            <ul class="pagination">
                <li class="page-item <?php if ($page == 1) echo 'disabled'; ?>">
                    <a class="page-link" href="search.php?searchTerm=<?php echo $searchTerm; ?>&page=<?php echo $page - 1; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item <?php if (count($games) < 12) echo 'disabled'; ?>">
                    <a class="page-link" href="search.php?searchTerm=<?php echo $searchTerm; ?>&page=<?php echo $page + 1; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</main>
<?php include 'footer.php';?>
</body>


