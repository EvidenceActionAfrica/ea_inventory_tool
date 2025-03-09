<?php
namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;

require_once __DIR__ . '/../config/Database.php';

class AuthUser {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    // Get all user profiles with their related fields
    public function getAllProfiles() {
        $sql = "SELECT au.*, d.name AS department_name, p.name AS position_name, 
                        o.name AS office_name, l.name AS location_name
                FROM auth_users au
                LEFT JOIN departments d ON au.department_id = d.id
                LEFT JOIN positions p ON au.position_id = p.id
                LEFT JOIN offices o ON au.office_id = o.id
                LEFT JOIN locations l ON au.location_id = l.id";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single user profile by ID
    public function getProfileById($id) {
        $sql = "SELECT * FROM auth_users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add a new user profile
    public function addProfile($name, $email, $department_id, $position_id, $office_id, $location_id, $role, $password) {
        $sql = "INSERT INTO auth_users (name, email, department_id, position_id, office_id, location_id, role, password)
                VALUES (:name, :email, :department_id, :position_id, :office_id, :location_id, :role, :password)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'department_id' => $department_id,
            'position_id' => $position_id,
            'office_id' => $office_id,
            'location_id' => $location_id,
            'role' => $role,
            'password' => $password
        ]);
    }

    // Update existing user profile
    public function updateProfile($id, $name, $email, $department_id, $position_id, $office_id, $location_id, $role, $password = null) {
        $sql = "UPDATE auth_users SET 
                    name = :name, email = :email, department_id = :department_id, 
                    position_id = :position_id, office_id = :office_id, location_id = :location_id, role = :role";
        if ($password) {
            $sql .= ", password = :password";
        }
        $sql .= " WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $params = [
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'department_id' => $department_id,
            'position_id' => $position_id,
            'office_id' => $office_id,
            'location_id' => $location_id,
            'role' => $role
        ];
        if ($password) {
            $params['password'] = $password;
        }
        $stmt->execute($params);
    }

    // Delete user profile
    public function deleteProfile($id) {
        $sql = "DELETE FROM auth_users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Get all roles for selection
    public function getRoles() {
        return ['super_admin', 'IT', 'QAQC', 'MLE'];
    }

    // Find a user by email for authentication
    public function findByEmail($email) {
        try {
            $stmt = $this->db->prepare("
                SELECT id, name, email, password, role
                FROM auth_users
                WHERE email = ?
            ");
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Error finding user by email: ' . $e->getMessage());
        }
    }

    // Update user password (used for password reset)
    public function updatePassword($email, $newPassword) {
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $stmt = $this->db->prepare("
                UPDATE auth_users
                SET password = ?
                WHERE email = ?
            ");
            $stmt->execute([$hashedPassword, $email]);
            return true;
        } catch (PDOException $e) {
            die('Error updating password: ' . $e->getMessage());
        }
    }
}
?>
