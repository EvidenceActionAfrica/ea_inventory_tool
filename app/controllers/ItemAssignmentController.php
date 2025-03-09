<?php
namespace App\Controllers;

// Include the necessary model and initialize
use App\Models\ItemAssignment;
use App\Models\AuthUser;
use App\Config\Database;
use PDO;
use PDOException;


class ItemAssignmentController {
    private $itemAssignment;
    private $conn;

    public function __construct() {
        $db = new Database(); // Create an instance of Database
        $this->conn = $db->connect();
        // Initialize the ItemAssignment model
        $this->itemAssignment = new ItemAssignment();
    }
        public function viewAssignments() {
            // Fetch all assignments or filtered based on GET parameters
            $search = $_GET['search'] ?? null;
            $acknowledged = $_GET['acknowledged'] ?? null;
    
            // Get the assignments data from the model
            $assignments = $this->itemAssignment->getAllAssignments($search, null, $acknowledged);
    
            // Include the view to render the assignments page
            require_once __DIR__ ."/../views/assignments/view_assignments.php";
        }

        // Show the Add Assignment form
public function showAddForm() {
    $itemAssignment = new ItemAssignment();
    $authUser = new AuthUser();
    
    $unassignedItems = $itemAssignment->getUnassignedItems();
    $users = $authUser->getAllProfiles();

    require_once __DIR__ . '/../views/assignments/add_assignment.php';
}

// Store the assignment (process form submission)
public function storeAssignment() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Collect and sanitize inputs
        $user_id = intval($_POST['user_id'] ?? 0);
        $inventory_ids = $_POST['inventory_id'] ?? [];
        $date_assigned = trim($_POST['date_assigned'] ?? '');
        $managed_by = trim($_POST['managed_by'] ?? '');

        // Validation checks
        if (!$user_id || empty($inventory_ids) || !$date_assigned || empty($managed_by)) {
            header('Location: ' . URL . 'item-assignments/add?error=All fields are required.');
            exit();
        }

        if (!is_array($inventory_ids) || count($inventory_ids) < 1 || count($inventory_ids) > 5) {
            header('Location: ' . URL . 'item-assignments/add?error=You must select between 1 and 5 items.');
            exit();
        }

        // Assign items
        $itemAssignment = new ItemAssignment();
        $result = $itemAssignment->assignItems($user_id, $inventory_ids, $date_assigned, $managed_by);

        // Redirect based on result
        if (strpos($result, 'Failed') === false) {
            header('Location: ' . URL . 'item-assignments?success=Items assigned successfully.');
        } else {
            header('Location: ' . URL . 'item-assignments/add?error=' . urlencode($result));
        }
        exit();
    }
}
        // Show the Edit Assignment form
        public function showEditForm() {
            $id = $_GET['id'] ?? null;
        
            if (!$id) {
                echo "Invalid assignment ID.";
                return;
            }
        
            $assignment = $this->getAssignmentById($id); // Fetch assignment data
        
            if (!$assignment) {
                echo "Assignment not found.";
                return;
            }
        
            require_once __DIR__ . '/../views/assignments/edit_assignment.php';
        }
        
        
        public function updateAssignment() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
        
                if (!isset($_SESSION['user_id'])) {
                    header('Location: ' . URL . 'login.php');
                    exit();
                }
        
                // Debugging to check if session works
                error_log('User ID from session: ' . $_SESSION['user_id']);
        
                // Collect and sanitize inputs
                $id = intval($_POST['id'] ?? 0);
                $user_id = intval($_POST['user_id'] ?? 0);
                $inventory_id = intval($_POST['inventory_id'] ?? 0);
                $date_assigned = trim($_POST['date_assigned'] ?? '');
                $managed_by = trim($_POST['managed_by'] ?? '');
        
                if (!$id || !$user_id || !$inventory_id || !$date_assigned || empty($managed_by)) {
                    header('Location: ' . URL . 'item-assignments/showEditForm?id=' . $id . '&error=All fields are required.');
                    exit();
                }
        
                $itemAssignment = new ItemAssignment();
                $result = $itemAssignment->updateAssignment($id, $user_id, $inventory_id, $date_assigned, $managed_by);
        
                if (strpos($result, 'Error') === false) {
                    header('Location: ' . URL . 'item-assignments?success=Assignment updated successfully.');
                } else {
                    header('Location: ' . URL . 'item-assignments/edit?id=' . $id . '&error=' . urlencode($result));
                }
                exit();
            } else {
                echo "Invalid request method.";
                exit();
            }
        }
      

        public function getAssignmentById($id) {
            try {
                $query = "SELECT ia.*, ia.acknowledged, au.name AS user_name, au.email, 
                                  d.name AS department, p.name AS position, 
                                  COALESCE(l.name, 'N/A') AS location, o.name AS office,
                                  c.category_name AS category, i.description, 
                                  i.serial_number, i.tag_number 
                           FROM item_assignments ia
                           JOIN auth_users au ON ia.user_id = au.id
                           JOIN inventory i ON ia.inventory_id = i.id
                           LEFT JOIN departments d ON au.department_id = d.id
                           LEFT JOIN positions p ON au.position_id = p.id
                           LEFT JOIN offices o ON au.office_id = o.id
                           LEFT JOIN locations l ON au.location_id = l.id
                           LEFT JOIN categories c ON i.category_id = c.id
                           WHERE ia.id = :id";
                
                $stmt = $this->conn->prepare($query);
                $stmt->execute(['id' => $id]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
                error_log('Raw assignment data: ' . json_encode($result)); // Detailed debug log
                return $result;
            } catch (PDOException $e) {
                error_log('Error fetching assignment: ' . $e->getMessage());
                return null;
            }
        }
        
        
        
        public function delete_assignment() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
                $id = intval($_POST['id']);
                error_log("Attempting to delete assignment with ID: " . $id);
                
                $assignment = $this->itemAssignment->getAssignmentById($id);
                if (!$assignment) {
                    error_log("Assignment not found.");
                    header('Location: ' . URL . 'item-assignments?error=Assignment not found.');
                    exit();
                }
        
                // Normalize 'acknowledged' value for comparison
                $acknowledged = strtolower(trim($assignment['acknowledged']));
                error_log("Assignment status: " . $acknowledged);
        
                if ($acknowledged !== 'pending') {
                    error_log("Cannot delete. Status: " . $acknowledged);
                    header('Location: ' . URL . 'item-assignments?error=Cannot delete acknowledged items.');
                    exit();
                }
        
                if ($this->itemAssignment->delete($id)) {
                    error_log("Assignment deleted successfully.");
                    header('Location: ' . URL . 'item-assignments?success=Assignment deleted.');
                } else {
                    error_log("Failed to delete assignment.");
                    header('Location: ' . URL . 'item-assignments?error=Failed to delete assignment.');
                }
                exit();
            } else {
                error_log("Invalid request.");
                header('Location: ' . URL . 'item-assignments?error=Invalid request.');
                exit();
            }
        }
        
        
        
             
        public function getPendingAssignments($user_id): array {
            try {
                $stmt = $this->conn->prepare("
                    SELECT 
                        ia.id,
                        au.name AS user_name,
                        au.email,
                        au.role AS department,
                        i.description,
                        i.serial_number,
                        ia.date_assigned,
                        ia.acknowledged
                    FROM item_assignments ia
                    LEFT JOIN inventory i ON ia.inventory_id = i.id
                    LEFT JOIN auth_users au ON ia.user_id = au.id
                    WHERE ia.user_id = :user_id 
                      AND ia.acknowledged = 'pending'
                ");
                $stmt->execute(['user_id' => $user_id]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log('Error fetching pending assignments: ' . $e->getMessage());
                return [];
            }
        }
        
        
        
    // Acknowledge a pending item assignment
    public function acknowledgeItem($assignment_id) {
        $stmt = $this->conn->prepare("
            UPDATE item_assignments
            SET acknowledged = 'acknowledged'
            WHERE id = :assignment_id AND acknowledged = 'pending'
        ");
        $stmt->execute(['assignment_id' => $assignment_id]);
        
        return $stmt->rowCount() > 0 
            ? "Item acknowledged successfully."
            : "No pending item found or already acknowledged.";
    }
    
    
}

// Instantiate controller and handle POST request
$controller = new ItemAssignmentController();

if ($_SERVER['REQUEST_URI'] === '/ea_inventory_tool/public/pending-assignments') {
    $user_id = $_SESSION['user']['id'] ?? null;
    $pendingAssignments = $controller->getPendingAssignments($user_id);
    require_once __DIR__ . "/../views/assignments/pending_assignments.php";
}

// Handle POST requests (acknowledgments)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acknowledge'])) {
    $assignment_id = $_POST['assignment_id'] ?? null;

    if ($assignment_id && is_numeric($assignment_id)) {
        $message = $controller->acknowledgeItem($assignment_id);
        require_once __DIR__ . "/../views/assignments/pending_assignments.php";
    } else {
        die("Invalid assignment ID.");
    }
}
?>