<?php
require_once __DIR__ . '/../config/Database.php';

class User extends Database {
    public function register($username, $password, $role, $full_name) {
        // Check if username already exists
        $check = $this->connect()->prepare("SELECT id FROM users WHERE username = ?");
        $check->execute([$username]);
        if ($check->rowCount() > 0) {
            return "exists"; // signal that username is taken
        }

        // Insert new user
        $sql = "INSERT INTO users (username, password, role, full_name) VALUES (?,?,?,?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $role, $full_name]);
        return true;
    }

    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
