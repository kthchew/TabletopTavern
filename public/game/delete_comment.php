#!/usr/local/bin/php
<?php
require '../../vendor/autoload.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rootPath = Tabletop\Config::getRootPath();
    Tabletop\Entities\Comment::deleteComment($_POST['comment_id']);
    header("Location: $rootPath/game/info.php?game_id=" . $_POST['game_id'] . "#bottom");
    exit;
}