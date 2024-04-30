#!/usr/local/bin/php
<?php
session_start();
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

<h1 class="text-center py-4">Start your journey...</h1>
<p class="text-center" style="font-size: 14pt"><b>Greetings, board game enthusiasts!</b> Have you been looking for your next game to play? </p>
<p class="text-center" style="font-size: 16pt">Find one here at the <b>Tabletop Tavern!</b></p>
<p class="text-center" style="font-size: 14pt"><b>Roll the dice</b> to get a random game or use our <b>filtered search</b> option to find exactly what you’re looking for.</p>
<br>

<div class="row mx-5">
    <div class="col-md m-2 text-center justify-content-center rounded">
        <div class="justify-content-center py-5" style="background-color: #cee4ac">
            <div>
                <h2>✴ Roll the Dice ✴</h2>
                <br>
                <img src="images/rollDice.png" alt="A 20 sided dice with a '?' on one face" style="width:200px;height:200px;">
                <br>
                <br>
                <a style="font-size: 18pt; background-color: #b4cd8c; color:#1C5E33;" class="btn py-3 mx-2 rounded" href="random.php" role="button">Take a chance!</a>
            </div>
        </div>
    </div>
    <div class="col-md m-2 text-center justify-content-center rounded">
        <div class="justify-content-center py-5" style="background-color: #cee4ac">
            <div>
                <h2>✴ Filter Search ✴</h2>
                <br>
                <img src="images/filter.png" alt="A shattered magnifying glass" style="width:200px;height:200px;">
                <br>
                <br>
                <a style="font-size: 18pt; background-color: #b4cd8c; color:#1C5E33;" class="btn py-3 mx-2 rounded" href="browse.php" role="button">Browse Games</a>
            </div>
        </div>
    </div>
</div>
<br>
<br>
<?php include 'footer.php';?>
</body>
</html>
