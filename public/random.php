#!/usr/local/bin/php
<?php
session_start();
define('__HEADER_FOOTER_PHP__', true);

require '../vendor/autoload.php';

use Tabletop\Entities\Game;
$randomGame = Game::getRandomGame();
if ($randomGame === null) {
    die("No games found");
} else {
    $config = parse_ini_file(__DIR__ . '/../config.ini');
    if (!$config) {
        $rootPath = '/TabletopTavern/public/';
    } else {
        $rootPath = $config['root_path'];
    }
    $location = $rootPath . "game/info.php?game_id={$randomGame->getId()}";

    header("Location: " . $location);
}
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
<p>You are being redirected to a random game... If you are not redirected, <a href="<?php echo $location; ?>">click here</a>.</p>
<?php include 'footer.php';?>
</body>
</html>