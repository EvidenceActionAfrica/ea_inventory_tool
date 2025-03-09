<?php
namespace App\controllers;

use App\models\Location;
use App\Models\Office;

class OfficeController {
    private $officeModel;
    private $locationModel;

    public function __construct() {
        $this->officeModel = new Office();
        $this->locationModel = new Location();
    }

    public function index() {
        $offices = $this->officeModel->getAll();
        $locations = $this->locationModel->getAll();
        include __DIR__ . '/../views/inventory/office_view.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $location_id = intval($_POST['location_id']);
            $this->officeModel->create($name, $location_id);
            header('Location: /ea_inventory_tool/public/offices?success=Office%20added%20successfully');
            exit();
        }
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $name = trim($_POST['name']);
            $location_id = intval($_POST['location_id']);
            $this->officeModel->update($id, $location_id, $name);
            header('Location: /ea_inventory_tool/public/offices?success=Office%20updated%20successfully');
            exit();
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {  // Corrected to 'id'
            $id = intval($_GET['id']);
            $this->officeModel->delete($id);
            header('Location: /ea_inventory_tool/public/offices?success=Office%20deleted%20successfully');
            exit();
        } else {
            // Optional: handle missing 'id'
            header('Location: /ea_inventory_tool/public/offices?error=Invalid%20request');
            exit();
        }
    }
    

}
