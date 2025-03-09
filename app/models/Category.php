<?php
namespace App\models;

use App\Config\Database;
use PDO;

class Category {
    private $conn;
    private $table_name = "categories";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Fetch all categories
    public function getCategories() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add new category
    public function addCategory($category_name, $description) {
        $query = "INSERT INTO " . $this->table_name . " (category_name, description) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$category_name, $description]);
    }

    // Get a single category by ID
    public function getCategoryById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update category
    public function updateCategory($id, $category_name, $description) {
        $query = "UPDATE " . $this->table_name . " SET category_name = ?, description = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$category_name, $description, $id]);
    }

    // Delete category
    public function deleteCategory($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>
