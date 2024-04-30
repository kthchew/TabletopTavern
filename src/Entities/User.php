<?php

namespace Tabletop\Entities;

use Tabletop\Database;

class User
{
    public int $id;
    public string $email;
    public string $username;

    /**
     * @return int The ID of the newly created user
     * @throws \Exception
     */
    static function createUser($email, $username, $password): int {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid email address");
        }
        if (!ctype_alnum($username)) {
            throw new \Exception("Username must be alphanumeric");
        }
        $MIN_PASSWORD_LENGTH = 8;
        if (strlen($password) < $MIN_PASSWORD_LENGTH) {
            throw new \Exception("Password must be at least $MIN_PASSWORD_LENGTH characters long");
        }

        $db = Database::getInstance();

        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $username, $password);
        $stmt->execute();
        if ($stmt->affected_rows != 1) {
            throw new \Exception("Failed to create user");
        }
        return $stmt->insert_id;
    }

    static function getUserById($id): User
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 1) {
            throw new \Exception("User not found");
        }
        $row = $result->fetch_assoc();
        $user = new User();
        $user->id = $row['id'];
        $user->email = $row['email'];
        $user->username = $row['username'];
        return $user;
    }

    /**
     * @throws \Exception if database has an error, user not found, or password is incorrect
     */
    static function getUserByNamePassword($username, $password): User
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 1) {
            throw new \Exception("User not found");
        }
        $row = $result->fetch_assoc();
        if (!password_verify($password, $row['password'])) {
            throw new \Exception("Invalid password");
        }
        $user = new User();
        $user->id = $row['id'];
        $user->email = $row['email'];
        $user->username = $row['username'];
        return $user;
    }
}