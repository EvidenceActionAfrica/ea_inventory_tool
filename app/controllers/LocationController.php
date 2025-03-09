<?php
namespace App\controllers;

use App\models\Location;

class LocationController {
    private $locationModel;

    public function __construct() {
        $this->locationModel = new Location();
    }

    public function index() {
        $locations = $this->locationModel->getAll();
        require_once __DIR__ . '/../views/inventory/location_view.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $this->locationModel->create($name);
            header('Location: /ea_inventory_tool/public/locations'); 
            exit();
        }
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $name = trim($_POST['name']);
            $this->locationModel->update($id, $name);
            header('Location: /ea_inventory_tool/public/locations'); 
            exit();
        }
    }

    public function delete() {
        if (isset($_GET['delete'])) {
            $id = intval($_GET['delete']);
            $this->locationModel->delete($id);
            header('Location: /ea_inventory_tool/public/locations'); 
            exit();
        }
    }
}
?>
