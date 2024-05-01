#!/usr/local/bin/php
<?php
require '../../vendor/autoload.php';
use Tabletop\Entities\User;
use Tabletop\Entities\Game;
use Tabletop\Database;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = $_POST['rating_value'];
    $game_id = $_GET['game_id'];

    if (isset($_SESSION['user'], $rating, $game_id)) {
        $userId = $_SESSION['user'];
        $rating = intval($rating);
        $gameId = intval($game_id);

        if (Game::getGameById($gameId) && User::getUserById($userId) && $rating >= 1 && $rating <= 5) {
            $db = Database::getInstance();
            $stmt = $db->prepare("SELECT * FROM ratings WHERE user_id = ? AND game_id = ?");
            $stmt->bind_param("ii", $userId, $gameId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $stmt = $db->prepare("UPDATE ratings SET rating_number = ? WHERE user_id = ? AND game_id = ?");
                $stmt->bind_param("iii", $rating, $userId, $gameId);
                $stmt->execute();
            } else {
                $stmt = $db->prepare("INSERT INTO ratings (user_id, game_id, rating_number) VALUES (?, ?, ?)");
                $stmt->bind_param("iii", $userId, $gameId, $rating);
                $stmt->execute();
            }
        }
    }
    $rootPath = Tabletop\Config::getRootPath();
    header("Location: $rootPath/game/info.php?game_id=$game_id");
}