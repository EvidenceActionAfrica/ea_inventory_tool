<?php

namespace App\Controllers;

use App\Config\Database; 
use PDO;

class HomeController {
    private $pdo;

    public function __construct() {
        // Initialize the database connection
        $database = new Database();
        $this->pdo = $database->connect();
    }

    public function index() {
        require_once __DIR__ . '/../config/config.php';
        require_once __DIR__ . '/../views/home/index.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;

            if (!$email || !$password) {
                echo "Email and Password are required!";
                return;
            }

            // Prepare SQL query to fetch user by email
            $stmt = $this->pdo->prepare("SELECT * FROM auth_users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user'] = $user;

                header('Location: /ea_inventory_tool/public/home');
                exit();
            } else {
                echo "Invalid login credentials!";
            }
        } else {
            require_once __DIR__ . '/../views/home/login.php';
        }
    }

    public function forgot_password() {
        require_once __DIR__ . '/../views/home/forgot_password.php';
    }
    public function logout() {
        require_once __DIR__ . '/../views/home/logout.php';
    }
}
