#!/usr/local/bin/php
<?php
require '../vendor/autoload.php';
session_start();
// get the search term from the form
$searchTerm = $_GET['searchTerm'];
$searchGenre = $_GET['searchGenre'];
$playerCount = $_GET['playerCount'];
$playTime = $_GET['playTime'];
$minAge = $_GET['minAge'];
$page = $_GET['page'] ?? 1;

use Tabletop\Entities\Game;

$genres = Game::getAllGenres();

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

<body>
<?php include 'header.php'; ?>

<main style="padding-left: 40px; padding-right: 40px; padding-bottom: 40px;">
    <h1 class="text-center py-4">Search Results</h1>
    <p class="text-center"><b>Results for: </b><span class="search-term-info"><?php echo $searchTerm; ?></span></p>
    <p class="text-center"><b>Page: </b><span class="page-num-info"><?php echo $page; ?></span></p>
    <br>

    <form class="row row-cols-1 row-cols-md-5 justify-content-center filter-form">
        <div class="col">
            <label for="searchTerm" class="form-label">Search by Name</label>
            <input type="text" class="form-control filter-term" id="searchTerm" name="searchTerm" placeholder="Search by Name..."
                   value="<?php echo $searchTerm ?? '' ?>">
        </div>
        <div class="col">
            <label for="searchGenre" class="form-label">Search by Genre</label>
            <select name="searchGenre" id="searchGenre" class="form-select filter-genre">
                <option value="">Search by Genre...</option>
                <?php foreach ($genres as $genre): ?>
                    <option value="<?php echo $genre; ?>" <?php if (isset($searchGenre) && $searchGenre == $genre) echo 'selected'; ?>><?php echo $genre; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col">
            <label for="playerCount" class="form-label">Number of Players</label>
            <input type="number" class="form-control filter-player" id="playerCount" name="playerCount" placeholder="Number of Players..."
                   value="<?php echo $playerCount ?? '' ?>">
        </div>
        <div class="col">
            <label for="playTime" class="form-label">Max Play Time (Minutes)</label>
            <input type="number" class="form-control filter-time" id="playTime" name="playTime" placeholder="Max Play Time (Minutes)..."
                   value="<?php echo $playTime ?? '' ?>">
        </div>
        <div class="col">
            <label for="minAge" class="form-label">Minimum Age</label>
            <input type="number" class="form-control filter-age" id="minAge" name="minAge" placeholder="Minimum Age..."
                   value="<?php echo $minAge ?? '' ?>">
        </div>
    </form>
    <br>
    <hr class="w-75 mx-auto">
    <br>

    <div style="text-align: center">
        <h4 id="errorMsg1"></h4>
        <p id="errorMsg2"></p>
    </div>

    <div class="w-100 d-flex justify-content-center d-none loading-indicator">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="row row-cols-md-4 row-cols-sm-2 row-cols-1 mb-4 game-card-container">
    </div>
    <div class="d-flex justify-content-center">
        <nav>
            <ul class="pagination">
                <li class="page-item disabled">
                    <button class="page-link prev-btn"
                            onclick="updatePage(<?php echo $page - 1; ?>)"
                            aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </button>
                </li>
                <li class="page-item disabled">
                    <button class="page-link next-btn"
                            onclick="updatePage(<?php echo $page + 1; ?>)"
                            aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </button>
                </li>
            </ul>
        </nav>
    </div>

</main>

<script>
    let timeout = null;
    $('.form-control, .form-select').on('input', function() {
        if (timeout) clearTimeout(timeout);
        timeout = setTimeout(() => {
            updatePage(1);
        }, 2000);
    });

    $(document).ready(function() {
        updatePage(<?php echo $page; ?>);
    });

    function updatePage(pageNumber) {
        const searchTerm = $('.filter-term').val();
        const searchGenre = $('.filter-genre').find(':selected').val();
        const playerCount = $('.filter-player').val();
        const playTime = $('.filter-time').val();
        const minAge = $('.filter-age').val();

        $('.game-card-container').html('');
        $('.loading-indicator').removeClass('d-none');
        fetch(`filter_json.php?searchName=${searchTerm}&searchGenre=${searchGenre}&playerCount=${playerCount}&playTime=${playTime}&minAge=${minAge}&page=${pageNumber}`, {
            method: 'GET'
        })
            .then(async response => {
                window.history.replaceState(null, null, `?searchTerm=${searchTerm}&searchGenre=${searchGenre}&playerCount=${playerCount}&playTime=${playTime}&minAge=${minAge}&page=${pageNumber}`);


                const gameCards = await response.json();
                $('.game-card-container').html('');
                gameCards.forEach(gameCard => {
                    $('.game-card-container').append(gameCard);
                });

                if(gameCards.length === 0){
                    document.getElementById("errorMsg1").innerHTML = "No games found";
                    document.getElementById("errorMsg2").innerHTML = "Please change or broaden your specifications";
                }

                const $prevBtn = $('.prev-btn');
                const $nextBtn = $('.next-btn');
                $prevBtn.attr('onclick', `updatePage(${pageNumber - 1})`);
                $nextBtn.attr('onclick', `updatePage(${pageNumber + 1})`);
                $('.page-item').removeClass('disabled');
                if (pageNumber === 1) {
                    $prevBtn.parent().addClass('disabled');
                }
                if (gameCards.length < 12) {
                    $nextBtn.parent().addClass('disabled');
                }


                $('.search-term-info').text(searchTerm);
                $('.page-num-info').text(pageNumber);

                $('.loading-indicator').addClass('d-none');
            });
    }
</script>
<?php include 'footer.php'; ?>
</body>



