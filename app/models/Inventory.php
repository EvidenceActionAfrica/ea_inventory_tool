<?php
namespace App\Models;  // Add this namespace declaration

require_once __DIR__ . "/../config/Database.php";
use App\Config\Database;
use PDO; 
use PDOException; 

class Inventory {
    private $conn;
    private $table_name = "inventory";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Fetch all inventory items
    public function getItems() {
        try {
            $query = "SELECT i.id, c.category_name AS category, i.description, i.serial_number, 
                             i.tag_number, i.acquisition_date, i.acquisition_cost, i.warranty_date 
                      FROM inventory i
                      JOIN categories c ON i.category_id = c.id
                      ORDER BY i.created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        } catch (PDOException $e) {
            die("Query error: " . $e->getMessage());
        }
    }
    

    // Fetch a single inventory item by ID
    public function getItemById($id) {
        // Ensure $id is an integer to prevent any potential security issues
        $id = intval($id);
    
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
    
        // Fetch the item, if it exists
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // If no item is found, return null
        if (!$item) {
            return null;
        }
    
        return $item;
    }
    

    // Add new inventory item
    public function addItem($category_id, $description, $serial_number, $tag_number, $acquisition_date, $acquisition_cost, $warranty_date) {
        try {
            if (strtotime($acquisition_date) > time()) {
                return "Error: Acquisition date cannot be in the future!";
            }
    
            $query = "INSERT INTO " . $this->table_name . " 
                      (category_id, description, serial_number, tag_number, acquisition_date, acquisition_cost, warranty_date) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$category_id, $description, $serial_number, $tag_number, $acquisition_date, $acquisition_cost, $warranty_date]);
    
            return "Item added successfully!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {  // Code 23000 is a UNIQUE constraint violation
                return "Error: Serial number or tag number already exists!";
            }
            return "Error: " . $e->getMessage();
        }
    }

    // Update inventory item
    public function updateItem($id, $category_id, $description, $serial_number, $tag_number, $acquisition_date, $acquisition_cost, $warranty_date) {
        try {
            $stmt = $this->conn->prepare("
                UPDATE inventory 
                SET category_id = :category_id, description = :description, serial_number = :serial_number, 
                    tag_number = :tag_number, acquisition_date = :acquisition_date, 
                    acquisition_cost = :acquisition_cost, warranty_date = :warranty_date
                WHERE id = :id
            ");
            $stmt->execute([
                'id' => $id,
                'category_id' => $category_id,
                'description' => $description,
                'serial_number' => $serial_number,
                'tag_number' => $tag_number,
                'acquisition_date' => $acquisition_date,
                'acquisition_cost' => $acquisition_cost,
                'warranty_date' => $warranty_date
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log('Error updating inventory item: ' . $e->getMessage());
            return false;
        }
    }
    
    

    // Delete inventory item
    public function deleteItem($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]) ? true : false;
    }

    // Search inventory by description, serial number, or tag number
        public function searchItems($query)
        {
            try {
                $searchQuery = "%$query%";
                $sql = "SELECT i.id, c.category_name AS category, i.description, i.serial_number,
                                i.tag_number, i.acquisition_date, i.acquisition_cost, i.warranty_date
                        FROM inventory i
                        JOIN categories c ON i.category_id = c.id
                        WHERE i.serial_number LIKE :query 
                           OR i.tag_number LIKE :query
                           OR i.description LIKE :query
                        ORDER BY i.created_at DESC";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':query', $searchQuery, PDO::PARAM_STR);
                $stmt->execute();
        
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Query error: " . $e->getMessage());
            }
        }
        

    // Get unassigned, functional, and repairable items
    public function getInStockItems() {
        $query = "
            SELECT i.id, c.category_name AS category, i.description, 
                   i.serial_number, i.tag_number, i.acquisition_date, i.warranty_date
            FROM inventory i
            LEFT JOIN categories c ON i.category_id = c.id
            LEFT JOIN item_assignments ia ON i.id = ia.inventory_id
            LEFT JOIN item_returns ir ON i.id = ir.item_id
            WHERE ia.id IS NULL 
              AND (ir.item_state = 'functional' OR ir.item_state = 'repairable' OR ir.item_state IS NULL)
              AND (ir.status = 'approved' OR ir.status IS NULL)
            ORDER BY i.acquisition_date DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getInUseItems() {
        $query = "
            SELECT i.id, c.category_name AS category, i.description, 
                   i.serial_number, i.tag_number, ia.date_assigned, au.name AS assigned_to
            FROM inventory i
            LEFT JOIN categories c ON i.category_id = c.id
            JOIN item_assignments ia ON i.id = ia.inventory_id
            JOIN auth_users au ON ia.user_id = au.id
            ORDER BY ia.date_assigned DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}
?>
