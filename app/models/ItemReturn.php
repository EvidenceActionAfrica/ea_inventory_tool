<?php
namespace App\Models;  // Add this namespace declaration

require_once __DIR__ . "/../config/Database.php";
use App\Config\Database;
use PDO; 
use Exception; 

class ItemReturn {
    private $conn;


    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Get assigned items for a user (excluding those already pending return)
    public function getUserAssignments($user_id) {
        $query = "SELECT ia.inventory_id AS item_id, i.description, i.serial_number, i.tag_number
                  FROM item_assignments ia
                  JOIN inventory i ON ia.inventory_id = i.id
                  LEFT JOIN item_returns ir ON ia.inventory_id = ir.item_id AND ir.status != 'pending'
                  WHERE ia.user_id = :user_id AND ir.item_id IS NULL";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all authorized item receivers
    public function getAllReceivers() {
        $query = "SELECT id, name FROM auth_users";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Record a pending return
    public function saveReturn($item_id, $receiver_id, $return_date) {
        // Prepare the SQL query to insert the return record
        $query = "INSERT INTO item_returns (item_id, receiver_id, return_date, status)
                  VALUES (:item_id, :receiver_id, :return_date, 'pending')";
    
        // Prepare and execute the statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':item_id', $item_id, PDO::PARAM_INT);
        $stmt->bindValue(':receiver_id', $receiver_id, PDO::PARAM_INT);
        $stmt->bindValue(':return_date', $return_date);
    
        // Execute the query
        $stmt->execute();
    }
    

    // Get pending returns for a receiver
    public function getPendingReturns($receiver_id) {
        $sql = "SELECT ir.id, ir.item_id, i.description, i.serial_number, au.name AS returned_by, ir.return_date, ir.status
                FROM item_returns ir
                JOIN inventory i ON ir.item_id = i.id
                JOIN auth_users au ON ir.user_id = au.id
                WHERE ir.receiver_id = :receiver_id AND ir.status = 'pending'";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':receiver_id', $receiver_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Fetch all disposed items (lost or unrepairable)
        public function getDisposedItems()
        {
            $sql = "SELECT ir.*, i.description, i.serial_number, 
                            c.category_name AS category_name, au.name AS returned_by
                    FROM item_returns ir
                    JOIN inventory i ON ir.item_id = i.id
                    JOIN categories c ON i.category_id = c.id
                    JOIN auth_users au ON ir.receiver_id = au.id
                    WHERE ir.status = 'approved'
                    AND (ir.item_state = 'lost' OR (ir.item_state = 'damaged' AND ir.repair_status = 'Unrepairable'))";
            
            $stmt = $this->conn->prepare($sql);
            
            if (!$stmt->execute()) {
                print_r($stmt->errorInfo()); // Show SQL errors for debugging
            }
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Flexible method to get items by state and optional repair status
        public function getItemReturnsByState($state, $repair_status = null)
        {
            $sql = "SELECT ir.*, i.description, i.serial_number, 
                            c.category_name AS category_name, au.name AS returned_by
                    FROM item_returns ir
                    JOIN inventory i ON ir.item_id = i.id
                    JOIN categories c ON i.category_id = c.id
                    JOIN auth_users au ON ir.receiver_id = au.id
                    WHERE ir.item_state = :state";
            
            if (!empty($repair_status)) {
                $sql .= " AND ir.repair_status = :repair_status";
            }

            $stmt = $this->conn->prepare($sql);

            $params = ['state' => $state];
            if (!empty($repair_status)) {
                $params['repair_status'] = $repair_status;
            }

            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    


    // Get all returns submitted by a user
    public function getUserReturns($user_id) {
        $sql = "SELECT ir.id, i.description, i.serial_number, au.name AS received_by, ir.return_date, ir.status
                FROM item_returns ir
                JOIN inventory i ON ir.item_id = i.id
                JOIN auth_users au ON ir.receiver_id = au.id
                WHERE ir.user_id = :user_id"; // Check that `ir.user_id` is valid
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Delete a pending return (for users)
    public function deletePendingReturn($return_id, $user_id) {
        $sql = "DELETE FROM item_returns WHERE id = :return_id AND user_id = :user_id AND status = 'pending'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':return_id', $return_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Approve a return and update item state
    public function approveReturn($return_id, $item_state, $approved_by) {
        $approved_date = date('Y-m-d H:i:s');
        $status = 'approved';
    
        $sql = "UPDATE item_returns 
                SET item_state = :item_state, status = :status, approved_by = :approved_by, approved_date = :approved_date 
                WHERE id = :return_id";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':item_state', $item_state, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':approved_by', $approved_by, PDO::PARAM_INT);
        $stmt->bindParam(':approved_date', $approved_date, PDO::PARAM_STR);
        $stmt->bindParam(':return_id', $return_id, PDO::PARAM_INT);
    
        return $stmt->execute();
    }

    // Update inventory status based on item state
    public function updateInventoryStatus($item_id, $item_state) {
        $asset_status = match ($item_state) {
            'functional' => 'instock',
            'damaged' => 'repairable',
            'lost' => 'disposed',
            default => null
        };

        if ($asset_status) {
            $sql = "UPDATE inventory SET asset_status = :asset_status WHERE id = :item_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':asset_status', $asset_status, PDO::PARAM_STR);
            $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }

     // Get lost items
     public function getLostItems($search = null) {
        $query = "SELECT i.id, c.category_name AS category, i.description, 
                         i.serial_number, i.tag_number, i.acquisition_date, i.warranty_date,
                         ir.status, ir.item_state, ir.return_date
                  FROM item_returns ir
                  INNER JOIN inventory i ON ir.item_id = i.id
                  LEFT JOIN categories c ON i.category_id = c.id
                  WHERE ir.status = 'approved'
                  AND ir.item_state = 'lost'";
    
        if ($search) {
            $query .= " AND (i.serial_number LIKE :search OR i.tag_number LIKE :search)";
        }
    
        $query .= " ORDER BY ir.return_date DESC";
    
        $stmt = $this->conn->prepare($query);
    
        if ($search) {
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    

    // Get all damaged items
    public function getAllDamagedItems() {
        $query = "SELECT * FROM item_returns WHERE item_state = 'damaged'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update repair status
    public function updateRepairStatus($item_id, $repair_status) {
        $sql = "UPDATE item_returns 
                   SET repair_status = :repair_status 
                   WHERE item_id = :item_id 
                   AND item_state = 'damaged' 
                   AND status = 'approved'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':repair_status', $repair_status, PDO::PARAM_STR);
        $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

?>
