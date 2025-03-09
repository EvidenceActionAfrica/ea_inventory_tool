<?php
namespace App\models;

use App\Config\Database;
use PDO;

class Location {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM locations");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($name) {
        $stmt = $this->conn->prepare("INSERT INTO locations (name) VALUES (:name)");
        return $stmt->execute(['name' => $name]);
    }

    public function update($id, $name) {
        $stmt = $this->conn->prepare("UPDATE locations SET name = :name WHERE id = :id");
        return $stmt->execute(['id' => $id, 'name' => $name]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM locations WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}