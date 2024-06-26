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
<div class="container">
    <div class="row align-items-center">
        <div class="col text-end" >
            <img src="images/tempLogo.png" class="d-none d-sm-inline" alt="A drawing of a d-20 dice with 'TT' inscribed on it" style="max-width: 70px; height: auto;">
        </div>
        <div class="col-8 col-sm-6 text-center">
            <h1>Start your journey with</h1>
            <h1>Tabletop Tavern!</h1>
        </div>
        <div class="col text-start">
            <img src="images/tempLogo.png" class="d-none d-sm-inline" alt="A drawing of a d-20 dice with 'TT' inscribed on it" style="max-width: 70px; height: auto;">
        </div>
    </div>

    <div class="row mt-4">
        <div class="col text-center" style = "padding-left: 100px; padding-right: 100px">
            <br>
            <h2 style="font-size: 24px;">Greetings, tabletop game enthusiasts!</h2>
            <br>
            <p style="font-size: 18px;" >Have you been looking for your next game to play? Or maybe you’re new to board games and want to find the perfect game for you. Find what you need here at the Tabletop Tavern!</p>
            <p style="font-size: 18px;" >Board games and tabletop games can be hard to get into and difficult to find... There’s just so many options out there! At the Tabletop Tavern you can filter a large database of games by genre, number of players, age rating, mechanics, and more!</p>
            <p style="font-size: 18px;" >Not sure what to filter by? Not a problem! Take our quiz to get a game suggested to you, or even “roll the dice” and find a completely random recommendation!</p>
            <br>
            <hr class="bold-divider">
            <h1 style="margin-bottom: 0;">Created By...</h1>
            <div class="hex-container">
                <div>
                    <div class="hexagon" >
                        <a href="https://github.com/kthchew">
                        <img src="images/kenneth_img.png" alt="Kenneth's GitHub Profile Picture" class= "image">
                        </a>
                    </div>
                    <h5>Kenneth Chew</h5>
                </div>
                <div>
                    <div class="hexagon">
                        <a href="https://github.com/annikajc">
                        <img src="images/annika_img.png" class= "image" alt="Annika's GitHub Profile Picture">
                        </a>
                    </div>
                    <h5>Annika Cruz</h5>
                </div>
                <div>
                    <div class="hexagon">
                        <a href="https://github.com/jonana-split">
                        <img src="images/joanna_img.png" alt="Joanna's GitHub Profile Picture" class= "image">
                        </a>
                    </div>
                    <h5>Joanna Mijares</h5>
                </div>
                <div>
                    <div class="hexagon">
                        <a href="https://github.com/ansh-kalariya">
                        <img src="images/ansh_img.png" alt="Ansh's GitHub Profile Picture" class= "image">
                        </a>
                    </div>
                    <h5>Ansh Kalariya</h5>
                </div>
                <div>
                    <div class="hexagon">
                        <a href="https://github.com/VallinEMCSQ">
                        <img src="images/cameron_img.png" alt="Cameron's GitHub Profile Picture" class= "image">
                        </a>
                    </div>
                    <h5>Cameron Vallin</h5>
                </div>
            </div>

        </div>
    </div>
</div>
<?php include 'footer.php';?>
</body>
</html>
