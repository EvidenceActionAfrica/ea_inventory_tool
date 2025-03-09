<?php
namespace App\Models;
use PDO;
use App\Config\Database;

class Department {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM departments ORDER BY created DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($name) {
        $stmt = $this->conn->prepare("INSERT INTO departments (name) VALUES (:name)");
        return $stmt->execute(['name' => $name]);
    }

    public function update($id, $name) {
        $stmt = $this->conn->prepare("UPDATE departments SET name = :name WHERE id = :id");
        return $stmt->execute(['id' => $id, 'name' => $name]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM departments WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>
