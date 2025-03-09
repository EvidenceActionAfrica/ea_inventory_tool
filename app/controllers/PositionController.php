<?php

namespace App\Controllers;

use App\Models\Position;

class PositionController {
    private $positionModel;

    public function __construct() {
        $this->positionModel = new Position();
    }

    public function index() {
        $positions = $this->positionModel->getAll();
        require_once __DIR__ . '/../views/inventory/position_view.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            if ($this->positionModel->create($name)) {
                $this->redirect('Position added successfully');
            }
        }
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            if ($this->positionModel->update($id, $name)) {
                $this->redirect('Position updated successfully');
            }
        }
    }

    public function delete() {
        if (isset($_GET['delete'])) {
            $id = $_GET['delete'];
            if ($this->positionModel->delete($id)) {
                $this->redirect('Position deleted successfully');
            }
        }
    }

    private function redirect($message) {
        header('Location: /ea_inventory_tool/public/positions?success=' . urlencode($message));
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'add':
                $name = $_POST['name'];
                $positionModel->addPosition($name);
                header("Location: positions.php?success=Position added successfully");
                exit;
            case 'update':
                $id = $_POST['id'];
                $name = $_POST['name'];
                $positionModel->updatePosition($id, $name);
                header("Location: positions.php?success=Position updated successfully");
                exit;
        }
    }
}

