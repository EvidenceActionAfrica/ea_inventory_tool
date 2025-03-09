<?php
namespace App\controllers;
use App\Models\Department;

class DepartmentController {
    private $departmentModel;

    public function __construct() {
        $this->departmentModel = new Department();
    }

    public function index() {
        $departments = $this->departmentModel->getAll();
        include_once __DIR__ . '/../views/inventory/department_view.php';
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add'])) {
                $this->store();
            } elseif (isset($_POST['update'])) {
                $this->update();
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
            $this->destroy();
        }
    }

    private function store() {
        $name = trim($_POST['name']);
        if (!empty($name)) {
            $result = $this->departmentModel->create($name);
            $this->redirect($result ? 'Department added successfully' : 'Failed to add department');
        }
    }

    private function update() {
        $id = $_POST['id'];
        $name = trim($_POST['name']);
        if (!empty($id) && !empty($name)) {
            $result = $this->departmentModel->update($id, $name);
            $this->redirect($result ? 'Department updated successfully' : 'Failed to update department');
        }
    }

    private function destroy() {
        $id = $_GET['delete'];
        if (!empty($id)) {
            $result = $this->departmentModel->delete($id);
            $this->redirect($result ? 'Department deleted successfully' : 'Failed to delete department');
        }
    }

    private function redirect($message) {
        $base_url = '/ea_inventory_tool/public/departments';
        header("Location: $base_url?success=" . urlencode($message));
        exit();
    }
   
}

$controller = new DepartmentController();
$controller->handleRequest();
$controller->index();
?>
