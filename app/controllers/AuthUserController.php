<?php

namespace App\Controllers;

use App\Models\AuthUser;
use App\Config\Database;
use PDO;

class AuthUserController {
    private $conn;
    private $authUserModel;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
        $this->authUserModel = new AuthUser();
    }

    public function index() {
        // Fetch Departments
        $departments = $this->fetchAll("SELECT id, name FROM departments");

        // Fetch Positions
        $positions = $this->fetchAll("SELECT id, name FROM positions");

        // Fetch Offices
        $offices = $this->fetchAll("SELECT id, name FROM offices");

        // Fetch all authorized users with related data
        $authUsersQuery = "
            SELECT 
                au.id, au.name, au.email, au.role,
                d.name AS department_name,
                p.name AS position_name,
                o.name AS office_name,
                l.name AS location_name
            FROM auth_users au
            LEFT JOIN departments d ON au.department_id = d.id
            LEFT JOIN positions p ON au.position_id = p.id
            LEFT JOIN offices o ON au.office_id = o.id
            LEFT JOIN locations l ON au.location_id = l.id
        ";

        $stmt = $this->conn->prepare($authUsersQuery);
        $stmt->execute();
        $authUsers = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];

        require_once __DIR__ . '/../views/authusers/auth_users.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            $department_id = $_POST['department_id'];
            $position_id = $_POST['position_id'];
            $office_id = $_POST['office_id'];
            
            // Auto-fetch location_id based on office_id
            $locationQuery = "SELECT location_id FROM offices WHERE id = :office_id";
            $stmt = $this->conn->prepare($locationQuery);
            $stmt->execute(['office_id' => $office_id]);
            $location = $stmt->fetch(PDO::FETCH_ASSOC);
            $location_id = $location['location_id'] ?? null;
    
            if (isset($_POST['add'])) { // Add new user
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
                $insertQuery = "
                    INSERT INTO auth_users (name, email, password, role, department_id, position_id, office_id, location_id) 
                    VALUES (:name, :email, :password, :role, :department_id, :position_id, :office_id, :location_id)
                ";
                $stmt = $this->conn->prepare($insertQuery);
                $stmt->execute([
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'role' => $role,
                    'department_id' => $department_id,
                    'position_id' => $position_id,
                    'office_id' => $office_id,
                    'location_id' => $location_id
                ]);
    
                header('Location: /auth_users?success=User added successfully');
                exit();
            } elseif (isset($_POST['update'])) { // Update existing user
                $id = $_POST['id'];
    
                $updateQuery = "
                    UPDATE auth_users
                    SET name = :name, email = :email, role = :role, department_id = :department_id,
                        position_id = :position_id, office_id = :office_id, location_id = :location_id
                    WHERE id = :id
                ";
                $stmt = $this->conn->prepare($updateQuery);
                $stmt->execute([
                    'name' => $name,
                    'email' => $email,
                    'role' => $role,
                    'department_id' => $department_id,
                    'position_id' => $position_id,
                    'office_id' => $office_id,
                    'location_id' => $location_id,
                    'id' => $id
                ]);
    
                header('Location: /auth_users?success=User updated successfully');
                exit();
            }
        }
    }
    public function edit() {
        if (!isset($_GET['id'])) {
            header('Location: /authusers?error=Invalid request');
            exit();
        }
    
        $id = $_GET['id'];
    
        // Fetch user by ID
        $userQuery = "
            SELECT au.*, d.name AS department_name, p.name AS position_name, o.name AS office_name, l.name AS location_name
            FROM auth_users au
            LEFT JOIN departments d ON au.department_id = d.id
            LEFT JOIN positions p ON au.position_id = p.id
            LEFT JOIN offices o ON au.office_id = o.id
            LEFT JOIN locations l ON au.location_id = l.id
            WHERE au.id = :id
        ";
    
        $stmt = $this->conn->prepare($userQuery);
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$user) {
            header('Location: /authusers?error=User not found');
            exit();
        }
    
        // Load departments, positions, and offices
        $departments = $this->fetchAll("SELECT id, name FROM departments");
        $positions = $this->fetchAll("SELECT id, name FROM positions");
        $offices = $this->fetchAll("SELECT id, name FROM offices");
    
        // Load the edit view
        require_once __DIR__ . '/../views/authusers/edit_auth_user.php';
    }
    

    

    public function destroy() {
        if (isset($_GET['delete'])) {
            $id = (int) $_GET['delete'];

            if ($this->authUserModel->deleteProfile($id)) {
                header('Location: ' . URL . 'auth_users?success=User deleted successfully');
            } else {
                header('Location: ' . URL . 'auth_users?error=Failed to delete user');
            }
            exit();
        }
    }


    private function fetchAll($query) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
    }
}

