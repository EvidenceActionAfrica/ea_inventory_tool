<?php
namespace App\Models;
use PDO;
use App\Config\Database;

class Office {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT offices.id, offices.name, locations.name AS location_name FROM offices JOIN locations ON offices.location_id = locations.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($name, $location_id) {
        // Check if location_id exists
        $stmt = $this->conn->prepare("SELECT id FROM locations WHERE id = ?");
        $stmt->execute([$location_id]);
        $location = $stmt->fetch();
    
        if (!$location) {
            die("Error: Invalid location_id $location_id. Location does not exist.");
        }
    
        // Insert office if location_id is valid
        $stmt = $this->conn->prepare("INSERT INTO offices (name, location_id) VALUES (:name, :location_id)");
        return $stmt->execute(['name' => $name, 'location_id' => $location_id]);
    }
    

    public function update($id, $location_id, $name) {
        $stmt = $this->conn->prepare("UPDATE offices SET location_id = :location_id, name = :name WHERE id = :id");
        return $stmt->execute(['id' => $id, 'location_id' => $location_id, 'name' => $name]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM offices WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM offices WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getLocationByOffice($office_id) {
        $stmt = $this->conn->prepare("
            SELECT location_id FROM offices WHERE id = :office_id
        ");
        $stmt->bindParam(':office_id', $office_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['location_id'] : null;
    }
    
    
}