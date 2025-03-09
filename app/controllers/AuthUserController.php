<?php

namespace App\Controllers;

use App\Models\AuthUser;
use App\Models\Office;
use App\Config\Database;
use PDO;

class AuthUserController {
    private $authUserModel;
    private $officeModel;

    public function __construct() {
        $this->authUserModel = new AuthUser();
        $this->officeModel = new Office();
    }

    // Display all user profiles
    public function index() {
        $profiles = $this->authUserModel->getAllProfiles();
        require_once __DIR__ . '/../views/authusers/auth_users.php';
    }

    // Get user by ID (for editing)
    public function getById($id) {
        return $this->authUserModel->getProfileById($id);
    }

    // Add or update user profile
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $department_id = $_POST['department_id'];
            $position_id = $_POST['position_id'];
            $office_id = $_POST['office_id'];
            $role = $_POST['role'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Get the location_id based on office selection
            $location_id = $this->officeModel->getLocationByOffice($office_id);

            // Create user with the retrieved location_id
            $this->authUserModel->addProfile($name, $email, $department_id, $position_id, $office_id, $location_id, $role, $password);

            header("Location: /auth_users");
            exit();
    }
}
        public function getLocation() {
            $office_id = $_POST['office_id'] ?? null;

            if ($office_id) {
                $location_id = $this->officeModel->getLocationByOffice($office_id);
                echo json_encode(['location_id' => $location_id ?: '']);
            } else {
                echo json_encode(['error' => 'Invalid office ID']);
            }
        }




    // Delete user profile
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $this->authUserModel->deleteProfile($id);
            $this->redirect('/ea_inventory_tool/public/auth_users.php?success=Profile deleted');
        }
    }

    private function redirect($url) {
        header("Location: $url");
        exit();

    }
    
}

