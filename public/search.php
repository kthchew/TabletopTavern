#!/usr/local/bin/php
<?php
require '../vendor/autoload.php';
session_start();
// get the search term from the form
$searchTerm = $_GET['searchTerm'];
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
<main class="container">
<h1 class="text-center py-4">Search Results</h1>
    <p class="text-center">Results for: <b><?php echo $searchTerm; ?></b></p>
    <p class="text-center">Page: <b><?php echo $page; ?></b></p>
    <br>
    <br>
    <div class="row row-cols-4 mb-4">

            <?php
            // get the games that match the search term
            $games = Game::searchGamesByName($searchTerm, 10, $page);
            $games = array_slice($games, 0, 10);
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
                <li class="page-item <?php if (count($games) < 10) echo 'disabled'; ?>">
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


