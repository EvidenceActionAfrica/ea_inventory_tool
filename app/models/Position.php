<?php
namespace App\Models;

use PDO;
use App\Config\Database;

class Position {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM positions");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($name) {
        $stmt = $this->conn->prepare("INSERT INTO positions (name) VALUES (:name)");
        return $stmt->execute(['name' => $name]);
    }

    public function update($id, $name) {
        $stmt = $this->conn->prepare("UPDATE positions SET name = :name WHERE id = :id");
        return $stmt->execute(['id' => $id, 'name' => $name]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM positions WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}