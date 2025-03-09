<?php
namespace App\Controllers;

use App\Models\ItemReturn;
use App\Config\Database;
use Exception;
use PDO;

class ItemReturnController {
    private $model;
    private $conn;

    public function __construct() {
        // Initialize database connection
        $database = new Database();
        $this->conn = $database->connect();
        $this->model = new ItemReturn($this->conn); // Pass the DB connection to the model
    }
    public function viewReturns() {
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Get user ID from session
        $user_id = $_SESSION['user']['id'] ?? null;
        
        // Check if the user ID exists
        if ($user_id) {
            try {
                // Fetch returns for the logged-in user
                $returns = $this->model->getUserReturns($user_id);
                
                // Check if returns are found
                if (!empty($returns)) {
                    // If there are returns, load the view
                    require_once __DIR__ . '/../views/assignments/returned_items.php';
                } else {
                    // If no returns, show a message
                    echo "No returns found for this user.";
                }
            } catch (Exception $e) {
                // Handle any errors that occur while fetching data
                echo "Error fetching returns: " . $e->getMessage();
            }
        } else {
            // If user is not logged in, display error message
            echo "User is not logged in.";
        }
    }

    public function showPendingReturns() {
        try {
            // Ensure session is active
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
    
            // Get the logged-in user's ID
            $receiver_id = $_SESSION['user']['id'] ?? null;
    
            if (!$receiver_id) {
                throw new Exception('User not logged in.');
            }
    
            // Fetch pending returns for the user
            $pendingReturns = $this->model->getPendingReturns($receiver_id);
    
            // Return JSON response for flexibility (for AJAX calls or API-like responses)
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'data' => $pendingReturns,
            ]);
            exit();
        } catch (Exception $e) {
            // Return error in JSON format
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
            exit();
        }
    }
    
    // Show all pending returns
    public function getPendingReturns() {
        try {
           
            // Check if user is logged in
            if (!isset($_SESSION['user']['id'])) {
                throw new Exception('User not logged in.');
            }
    
            // Get logged-in user's ID
            $receiver_id = $_SESSION['user']['id'];
    
            // Fetch pending returns for the logged-in user
            $pendingReturns = $this->model->getPendingReturns($receiver_id);
    
            // Ensure $pendingReturns is always set, even if empty
            $pendingReturns = $pendingReturns ?? [];
    
            // Load the view with pending returns data
            include __DIR__ . "/../views/assignments/pending.php";
    
        } catch (Exception $e) {
            // Handle errors (consider logging for production use)
            echo "<p style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
    
    // Get all damaged items for repair
    public function getRepairItems() {
        try {
            $damagedItems = $this->model->getAllDamagedItems();
            require_once __DIR__ . '/../views/inventory/repairs.php'; // Adjust path to your view
        } catch (Exception $e) {
            echo "Error fetching repair items: " . $e->getMessage();
        }
    }

    public function getDisposedItems()
    {
        $itemReturnModel = new \App\Models\ItemReturn();
        $disposedItems = $itemReturnModel->getDisposedItems();
        // Load the disposed items view
        require_once '../app/Views/inventory/disposed.php';
        exit(); // Ensure nothing runs after this
    }

    // Approve a return and update item state and inventory status
    public function approveReturn() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $returnId = $_POST['return_id'] ?? null;
            $itemId = $_POST['item_id'] ?? null;
            $itemState = $_POST['item_state'] ?? null;

            if (!$returnId || !$itemId || !$itemState) {
                $_SESSION['error'] = "Invalid input. Please try again.";
                header('Location: ' . URL . 'collections/pending');
                exit();
            }

            try {
                $sql = "UPDATE item_returns 
                        SET status = 'approved', 
                            item_state = :item_state, 
                            approved_by = :approved_by, 
                            approved_date = NOW()
                        WHERE id = :return_id";

                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':item_state', $itemState, PDO::PARAM_STR);
                $stmt->bindParam(':approved_by', $_SESSION['user']['id'], PDO::PARAM_INT);
                $stmt->bindParam(':return_id', $returnId, PDO::PARAM_INT);
                $stmt->execute();

                // Update inventory status
                $this->updateInventoryStatus($itemId, $itemState);

                $_SESSION['success'] = "Item return approved successfully!";
            } catch (Exception $e) {
                $_SESSION['error'] = "Error approving return: " . $e->getMessage();
            }

            header('Location: ' . URL . 'collections/pending');
            exit();
        }

        header('Location: ' . URL . 'collections/pending');
        exit();
    }

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
    // Method to show the form
    public function showReturnForm() {
        // Assuming you're getting the user ID from the session
        $user_id = $_SESSION['user']['id'] ?? null;

        if (!$user_id) {
            die("User not logged in. Session issue.");
        }

        // Fetch assigned items for the logged-in user
        $itemReturnModel = new ItemReturn();
        $items = $itemReturnModel->getUserAssignments($user_id); // Pass the logged-in user ID
        $receivers = $itemReturnModel->getAllReceivers(); // Get all receivers


        // Include the view to show the form
        include __DIR__ . '/../views/assignments/record_collection.php';
    }

    // Method to handle form submission and record the return
    public function recordReturn() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item_ids = $_POST['inventory_ids'] ?? [];
            $return_date = $_POST['return_date'] ?? '';
            $receiver_id = $_POST['receiver_id'] ?? '';

            // Check if required fields are set
            if ($item_ids && $return_date && $receiver_id) {
                // Save the return
                $itemReturn = new ItemReturn();
                $itemReturn->saveReturn($item_ids, $receiver_id, $return_date);

                // Redirect to avoid resubmitting the form
                header("Location: " . URL . "item-returns/record?success=true");
                exit;
            } else {
                // Redirect with error message
                header("Location: " . URL . "item-returns/record?error=true");
                exit;
            }
        }
    }
    
    
    
    // Delete a pending return
    public function deletePendingReturn($return_id) {
        $user_id = $_SESSION['user_id'] ?? null;

        if (!$user_id || !$return_id) {
            header('Location: ' . URL . 'item-returns/record?error=Unauthorized action.');
            exit;
        }

        try {
            $this->model->deletePendingReturn($return_id, $user_id);
            header('Location: ' . URL . 'item-returns?success=Pending return deleted.');
            exit;
        } catch (Exception $e) {
            header('Location: ' . URL . 'assignments/record_collection?error=Failed to delete pending return.');
            exit;
        }
    }

    // Get logged-in user's assigned items
    public function showUserItems() {
        $user_id = $_SESSION['user_id'] ?? null;
        try {
            return $user_id ? $this->model->getUserAssignments($user_id) : [];
        } catch (Exception $e) {
            return [];
        }
    }

    // Get returns submitted by the logged-in user
      public function showUserReturns() {
        $user_id = $_SESSION['user']['id']; // Get logged-in user ID
        
        // Create model instance and fetch user items
        $itemReturn = new ItemReturn();
        return $itemReturn->getUserAssignments($user_id);
    }

    // Get all authorized item receivers
    public function getAllReceivers() {
        try {
            return $this->model->getAllReceivers();
        } catch (Exception $e) {
            return [];
        }
    }

    // Get lost items
    public function getLostItems() {
        $search = $_GET['search'] ?? null;
    
        try {
            $lostItems = $this->model->getLostItems($search);
    
            if (empty($lostItems)) {
                $noResultsMessage = "No items found matching '$search'.";
            }
    
            require_once __DIR__ . '/../views/inventory/inventory_lost.php';
        } catch (Exception $e) {
            echo "Error fetching lost items: " . $e->getMessage();
        }
    }
    

    // Update repair status and inventory
    public function updateRepairStatus() {
        $item_id = $_POST['item_id'] ?? null;
        $repair_status = $_POST['repair_status'] ?? null;
    
        if (!$item_id || !in_array($repair_status, ['Repairable', 'Unrepairable'])) {
            header('Location: ' . URL . 'collections/repairs?error=Invalid data provided.');
            exit();
        }
    
        try {
            $this->model->updateRepairStatus($item_id, $repair_status);
            $new_asset_status = $repair_status === 'Repairable' ? 'instock' : 'disposed';
            $this->model->updateInventoryStatus($item_id, $new_asset_status);
    
            $redirect = $repair_status === 'Repairable' ? 'inventory_instock' : 'disposed';
            header('Location: ' . URL . "collections/{$redirect}?success=Item repair status updated.");
            exit();
        } catch (Exception $e) {
            header('Location: ' . URL . 'collections/repairs?error=Failed to update repair status.');
            exit();
        }
    }
      
}
if ($_SERVER['REQUEST_URI'] == '/item-returns/record_collection') {
    $controller = new ItemReturnController();
    $controller->recordReturn();
}

?>
