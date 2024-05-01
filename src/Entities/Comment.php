<?php

namespace Tabletop\Entities;

use Tabletop\Database;

class Comment
{
    public int $id;
    public int $userId;
    public int $gameId;
    public string $comment_text;
    public string $created_at;

    static function createComment($userId, $gameId, $comment_text): int {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO comments (user_id, game_id, comment_text, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $userId, $gameId, $comment_text);

        $stmt->execute();
        return $stmt->insert_id;
    }

    static function getCommentsByGameId($gameId): array {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE game_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $gameId);
        $stmt->execute();
        $result = $stmt->get_result();
        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comment = new Comment();
            $comment->id = $row['id'];
            $comment->userId = $row['user_id'];
            $comment->username = $row['username'];
            $comment->gameId = $row['game_id'];
            $comment->comment_text = $row['comment_text'];
            $comment->created_at = $row['created_at'];
            $comments[] = $comment;
        }
        return $comments;
    }

    static function updateComment($commentId, $comment_text): void {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE comments SET comment_text = ? WHERE id = ?");
        $stmt->bind_param("si", $comment_text, $commentId);
        $stmt->execute();
    }

    static function deleteComment($commentId): void {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->bind_param("i", $commentId);
        $stmt->execute();
    }

    static function getCommentById($id): ?Comment {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM comments WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
            $comment = new Comment();
            $comment->id = $row['id'];
            $comment->userId = $row['user_id'];
            $comment->gameId = $row['game_id'];
            $comment->comment_text = $row['comment_text'];
            $comment->created_at = $row['created_at'];
            return $comment;
        } else {
            return null;
        }
    }
}