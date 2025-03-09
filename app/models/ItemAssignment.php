<?php
namespace App\Models;

require_once __DIR__ . '/../config/Database.php';
use App\Config\Database;
use PDO; 
use PDOException;
class ItemAssignment {
    private $conn;
    

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAllAssignments($search = null, $user_id = null, $acknowledged = null) {
        // Base query
        $query = "SELECT ia.id, 
                          au.name AS user_name, au.email, 
                          d.name AS department, p.name AS position, 
                          COALESCE(l.name, 'N/A') AS location, o.name AS office,
                          c.category_name AS category, i.description,
                          i.serial_number, i.tag_number, 
                          ia.date_assigned, ia.managed_by, ia.created,
                          ia.acknowledged
                   FROM item_assignments ia
                   JOIN auth_users au ON ia.user_id = au.id
                   JOIN inventory i ON ia.inventory_id = i.id
                   LEFT JOIN departments d ON au.department_id = d.id
                   LEFT JOIN positions p ON au.position_id = p.id
                   LEFT JOIN offices o ON au.office_id = o.id
                   LEFT JOIN locations l ON au.location_id = l.id
                   LEFT JOIN categories c ON i.category_id = c.id";
    
        // Initialize conditions array
        $conditions = [];
        $params = [];
    
        // Search filter
        if ($search) {
            $conditions[] = "(au.name LIKE :search OR i.tag_number LIKE :search OR i.serial_number LIKE :search)";
            $params[':search'] = "%$search%";
        }
    
        // User filter
        if ($user_id) {
            $conditions[] = "ia.user_id = :user_id";
            $params[':user_id'] = $user_id;
        }
    
        // Acknowledged filter (pending or acknowledged)
        if ($acknowledged) {
            $conditions[] = "ia.acknowledged = :acknowledged";
            $params[':acknowledged'] = $acknowledged;
        }
    
        // Combine conditions with WHERE or AND
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }
    
        // Sort results by created date
        $query .= " ORDER BY ia.created DESC";
    
        // Prepare the query
        $stmt = $this->conn->prepare($query);
    
        // Bind all parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
    
        // Execute and fetch results
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Assign items to a user
    public function assignItems($user_id, $inventory_ids, $date_assigned, $managed_by) {
        if (!$user_id || !is_array($inventory_ids) || count($inventory_ids) < 1 || count($inventory_ids) > 5) {
            return 'Failed: Invalid data provided.';
        }

        try {
            $this->conn->beginTransaction();

            foreach ($inventory_ids as $inventory_id) {
                // Ensure the item is not already assigned
                $checkStmt = $this->conn->prepare("SELECT * FROM item_assignments WHERE inventory_id = :inventory_id");
                $checkStmt->execute(['inventory_id' => $inventory_id]);
                if ($checkStmt->fetch()) {
                    throw new \Exception('Failed: One or more items are already assigned.');
                }

                // Assign the item
                $stmt = $this->conn->prepare("
                    INSERT INTO item_assignments (user_id, inventory_id, date_assigned, managed_by, created)
                    VALUES (:user_id, :inventory_id, :date_assigned, :managed_by, NOW())
                ");

                $stmt->execute([
                    'user_id' => $user_id,
                    'inventory_id' => $inventory_id,
                    'date_assigned' => $date_assigned,
                    'managed_by' => $managed_by
                ]);
            }

            $this->conn->commit();
            return 'Success: Items assigned successfully.';
        } catch (\Exception $e) {
            $this->conn->rollBack();
            return 'Failed: ' . $e->getMessage();
        }
    }
    
    public function getAssignById($id) {
        try {
            $query = "SELECT * FROM item_assignments WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Error fetching assignment: ' . $e->getMessage());
        }
    }

    public function updateAssignment($id, $user_id, $inventory_id, $date_assigned, $managed_by) {
        // Check if the assignment is pending (or another status indicating it's not acknowledged)
        $checkQuery = "SELECT acknowledged FROM item_assignments WHERE id = :id";
        $stmt = $this->conn->prepare($checkQuery);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result && $result['acknowledged'] !== 'pending') {
            // If the status is not pending, return an error message
            return "Error: Assignment is already acknowledged or not in 'pending' status and cannot be edited.";
        }
    
        // Proceed with the update if the assignment is pending
        $query = "UPDATE item_assignments 
                  SET user_id = :user_id, inventory_id = :inventory_id, 
                      date_assigned = :date_assigned, managed_by = :managed_by
                  WHERE id = :id";
    
        $stmt = $this->conn->prepare($query);
        try {
            $stmt->execute([
                'id' => $id,
                'user_id' => $user_id,
                'inventory_id' => $inventory_id,
                'date_assigned' => $date_assigned,
                'managed_by' => $managed_by
            ]);
            return "Assignment updated successfully.";
        } catch (PDOException $e) {
            return "Error updating assignment: " . $e->getMessage();
        }
    }
    
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM item_assignments WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    

    public function acknowledgeItems($assignment_ids) {
        $stmt = $this->conn->prepare("
            UPDATE item_assignments 
            SET acknowledged = 'acknowledged'
            WHERE id = :assignment_id
        ");
    
        try {
            foreach ($assignment_ids as $assignment_id) {
                $stmt->execute(['assignment_id' => $assignment_id]);
            }
            return "Items acknowledged successfully.";
        } catch (PDOException $e) {
            return "Failed to acknowledge items: " . $e->getMessage();
        }
    }
    

    public function getAssignmentById($id) {
        $sql = "SELECT id, user_id, inventory_id, date_assigned, managed_by FROM item_assignments WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Returns an associative array with the assignment details
    }

    // Method to fetch assignments for the logged-in user
    public function showUserAssignments() {
        // Get the logged-in user's ID from the session
        $user_id = $_SESSION['user_id']; // Assuming the user ID is stored in the session

        if (!isset($user_id)) {
            $_SESSION['message'] = "Error: You must be logged in to view your assignments.";
            header("Location: /login"); // Redirect to login if user ID is not set
            exit();
        }

        // Fetch all assignments for the logged-in user
        return $this->getAllAssignments(null, $user_id); // Fetch assignments for this user
    }

    public function getUserAssignments($user_id) {
        $query = "SELECT ia.id, 
                         au.name AS user_name, au.email, 
                         d.name AS department, p.name AS position, 
                         COALESCE(l.name, 'N/A') AS location, o.name AS office,
                         c.category_name AS category, i.description,
                         i.serial_number, i.tag_number, 
                         ia.date_assigned, ia.managed_by, ia.created,
                         ia.acknowledged
                  FROM item_assignments ia
                  JOIN auth_users au ON ia.user_id = au.id
                  JOIN inventory i ON ia.inventory_id = i.id
                  LEFT JOIN departments d ON au.department_id = d.id
                  LEFT JOIN positions p ON au.position_id = p.id
                  LEFT JOIN offices o ON au.office_id = o.id
                  LEFT JOIN locations l ON au.location_id = l.id
                  LEFT JOIN categories c ON i.category_id = c.id
                  LEFT JOIN item_returns ir ON ia.inventory_id = ir.item_id 
                                            AND ir.status = 'approved'
                  WHERE ia.user_id = :user_id
                    AND (ir.id IS NULL OR ir.status != 'approved') -- Exclude approved returns
                  ORDER BY ia.created DESC";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
    
    

    public function getUnassignedItems() {
        $query = "
            -- Get unassigned items (never assigned before)
            SELECT i.id, i.description, i.serial_number, i.tag_number
            FROM inventory i
            LEFT JOIN item_assignments ia ON i.id = ia.inventory_id
            WHERE ia.inventory_id IS NULL
            
            UNION
            
            -- Get approved functional items (returned and now available)
            SELECT i.id, i.description, i.serial_number, i.tag_number
            FROM item_returns ir
            JOIN inventory i ON ir.item_id = i.id
            WHERE ir.item_state = 'functional' AND ir.status = 'approved'
            
            UNION
            
            -- Get repairable items (damaged but fixable)
            SELECT i.id, i.description, i.serial_number, i.tag_number
            FROM item_returns ir
            JOIN inventory i ON ir.item_id = i.id
            WHERE ir.item_state = 'damaged' AND ir.repair_status = 'Repairable' AND ir.status = 'approved'
        ";
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
 
}
?>
