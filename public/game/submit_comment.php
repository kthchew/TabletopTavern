#!/usr/local/bin/php
<?php
require '../../vendor/autoload.php';
session_start();

$rootPath = Tabletop\Config::getRootPath();

if (!isset($_SESSION['user'])) {
    header("Location: $rootPath/login.php");
    exit;
}

$userId = $_SESSION['user'];
$gameId = $_POST['game_id'];
$comment_text = $_POST['comment_text'];

Tabletop\Entities\Comment::createComment($userId, $gameId, $comment_text);

header("Location: $rootPath/game/info.php?game_id=" . $gameId);
exit;