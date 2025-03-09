<?php
namespace App\Controllers;

namespace App\Controllers;

use App\Models\Inventory;
use App\Models\Category;
use App\Config\Database; // Include the Database class
use PDO;
ini_set('display_errors', 1);
error_reporting(E_ALL);

class InventoryController {
    protected $inventory;
    private $db;

    public function __construct() {
        // Initialize Inventory model
        $this->inventory = new Inventory();
        
        // Initialize database connection
        $database = new Database();
        $this->db = $database->connect();
    }

    


    public function viewInventory()
    {
        $inventoryModel = new Inventory();
        $search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
        $items = !empty($search_query) ? $inventoryModel->searchItems($search_query) : $inventoryModel->getItems();

        require_once '../app/views/inventory/view_inventory.php';
    }
    
        // Get in-stock items (unassigned, functional, and repairable items)
        public function getInStockItems() {
            // Get the search query from GET, if available
            $search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
            
            // Get items based on the search query or fetch all in-stock items
            $items = !empty($search_query) ? $this->inventory->searchItems($search_query) : $this->inventory->getInStockItems();
            
            // Set a message if no items are found
            $no_results_message = empty($items) ? "No results found" : '';
        
            // Pass variables to the view
            require_once __DIR__ . '/../views/inventory/inventory_instock.php';
        }
        

    // Get in-use items (assigned items)
    public function getInUseItems() {
        $search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        // Call the model method to get in-use items (based on the search query)
        $items = !empty($search_query) ? $this->inventory->searchItems($search_query) : $this->inventory->getInUseItems();
        
        // If no items were found, set a 'no results' message
        $no_results_message = empty($items) ? "No results found" : '';
    
        // Pass items and no_results_message to the view
        require_once __DIR__ . '/../views/inventory/inventory_inuse.php';
    }
    


    // ADD ITEM
    public function addItem() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
            // Process form submission
            $category_id = intval($_POST['category_id'] ?? 0);
            $description = $_POST['description'] ?? '';
            $serial_number = $_POST['serial_number'] ?? '';
            $tag_number = $_POST['tag_number'] ?? '';
            $acquisition_date = $_POST['acquisition_date'] ?? '';
            $acquisition_cost = $_POST['acquisition_cost'] ?? '';
            $warranty_date = $_POST['warranty_date'] ?? '';
    
            if (!$category_id) {
                header('Location: ' . URL . 'inventory/add?error=Category is required');
                exit();
            }
    
            // Add item via model
            $result = $this->inventory->addItem($category_id, $description, $serial_number, $tag_number, $acquisition_date, $acquisition_cost, $warranty_date);
    
            // Redirect with success or error message
            header("Location: " . URL . "inventory?" . ($result ? "success=Item added successfully!" : "error=Failed to add item."));
            exit();
    
        } else {
            // Show the add item form
            include __DIR__ . "/../views/inventory/add_item.php";
            exit();
        }
    }
    

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category_id = $_POST['category_id'];
            $description = $_POST['description'];
            $serial_number = $_POST['serial_number'];
            $tag_number = $_POST['tag_number'];
            $acquisition_date = $_POST['acquisition_date'];
            $acquisition_cost = $_POST['acquisition_cost'];
            $warranty_date = $_POST['warranty_date'];

            $inventory = new Inventory();
            $result = $inventory->addItem($category_id, $description, $serial_number, $tag_number, $acquisition_date, $acquisition_cost, $warranty_date);

            if ($result) {
                header('Location: ' . URL . 'inventory?success=Item added successfully');
                exit();
            } else {
                header('Location: ' . URL . 'inventory/add?error=Failed to add item');
                exit();
            }
        }
    }
        // Show the form to edit an item
        public function showEditForm()
        {
            $id = $_GET['id'] ?? null;

            if (!$id) {
                die('Item ID is required.');
            }

            $item = $this->inventory->getItemById($id);

            if (!$item) {
                die('Item not found.');
            }

            require_once __DIR__ . '/../views/inventory/edit_item.php';
        }

        
        public function updateItem()
{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id = intval($_POST['id'] ?? 0);
        $category_id = intval($_POST['category_id'] ?? 0);
        $description = $_POST['description'] ?? '';
        $serial_number = $_POST['serial_number'] ?? '';
        $tag_number = $_POST['tag_number'] ?? '';
        $acquisition_date = $_POST['acquisition_date'] ?? '';
        $acquisition_cost = $_POST['acquisition_cost'] ?? '';
        $warranty_date = $_POST['warranty_date'] ?? '';

        if (!$id || !$category_id) {
            header('Location: ' . URL . 'inventory/edit?id=' . $id . '&error=Missing required fields.');
            exit();
        }

        // Update item in database
        $result = $this->inventory->updateItem($id, $category_id, $description, $serial_number, $tag_number, $acquisition_date, $acquisition_cost, $warranty_date);

        // Redirect
        header("Location: " . URL . "inventory?" . ($result ? "success=Item updated successfully!" : "error=Failed to update item."));
        exit();
    }
}

        
    public function searchInventory() {
        $search_query = $_GET['search'] ?? '';
    
        // Validate and sanitize the search query
        $search_query = htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8');
    
        // Call the model method to search inventory items
        $search_results = $this->inventory->searchItems($search_query);
    
        // Pass search results and query to the view
        $items = $search_results; // Assign search results to $items
        require_once '../app/views/inventory/view_inventory.php';
    }
    
    
    
}
