#!/usr/local/bin/php
<?php
require '../../vendor/autoload.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$userId = $_SESSION['user'];
$gameId = $_POST['game_id'];
$comment_text = $_POST['comment_text'];

Tabletop\Entities\Comment::createComment($userId, $gameId, $comment_text);

header("Location: info.php?game_id=" . $gameId);
exit;