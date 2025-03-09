<?php
namespace App\Controllers;
use App\Models\Category;

class CategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new Category();
    }

    // Display categories
    public function index() {
        $categories = $this->categoryModel->getCategories();
        require_once __DIR__ . "/../Views/inventory/categories.php";
    }

    // Add new category
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $category_name = trim($_POST['category_name']);
            $description = trim($_POST['description']);
            $this->categoryModel->addCategory($category_name, $description);
            header("Location: " . URL . "categories?success=Category Added Successfully!");
            exit();
        }
    }

    // Edit category
    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = intval($_POST['id']);
            $category_name = trim($_POST['category_name']);
            $description = trim($_POST['description']);
            $this->categoryModel->updateCategory($id, $category_name, $description);
            header("Location: " . URL . "categories?success=Category Added Successfully!");
            exit();
        }
    }

    // Delete category
    public function delete() {
        if (isset($_GET['delete'])) {
            $id = intval($_GET['delete']);
            $this->categoryModel->deleteCategory($id);
            header("Location: " . URL . "categories?success=Category Added Successfully!");
            exit();
        }
    }
}

?>
