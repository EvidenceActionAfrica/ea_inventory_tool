<?php

use App\Controllers\HomeController;
use App\Controllers\AuthUserController;
use App\Controllers\ItemAssignmentController;
use App\Controllers\ItemReturnController;
use App\Controllers\InventoryController;
use App\Controllers\CategoryController;
use App\Controllers\DepartmentController;
use App\Controllers\PositionController;
use App\Controllers\OfficeController;
use App\Controllers\LocationController;

// Define all routes here
$routes = [
    // login routes
    'home' => [HomeController::class, 'index'],
    'home/login' => [HomeController::class, 'login'],
    'home/forgot_password' => [HomeController::class, 'forgot_password'],
    'home/logout' => [HomeController::class, 'logout'],

    // Inventory Routes
    'inventory' => [InventoryController::class, 'viewInventory'], // View all inventory items
    'inventory/search' => [InventoryController::class, 'searchInventory'], // Search inventory items
    'inventory/add' => [InventoryController::class, 'addItem'], // Show the add item form
    'inventory/store' => [InventoryController::class, 'store'], // Handle the add item form submission
    'inventory/edit' => [InventoryController::class, 'showEditForm'], // Edit form (expects ?id=1)
    'inventory/update' => [InventoryController::class, 'updateItem'],  // Update (expects ?id=1)
    'inventory/delete' => [InventoryController::class, 'deleteItem'], // Handle the delete item request

    // assignments routes
    'item-assignments' => [ItemAssignmentController::class, 'viewAssignments'], // View all assignments
    'item-assignments/search' => [ItemAssignmentController::class, 'viewAssignments'], // Search for assignments
    'item-assignments/showadd' => [ItemAssignmentController::class, 'showAddForm'], // Add new item assignment form
    'item-assignments/store' => [ItemAssignmentController::class, 'storeAssignment'], // Process form submission
    'item-assignments/showEditForm' => [ItemAssignmentController::class, 'showEditForm'],
    'item-assignments/updateAssignment' => [ItemAssignmentController::class, 'updateAssignment'],
    'item-assignments/delete' => [ItemAssignmentController::class, 'delete_assignment'], // Delete an assignment
    'item-assignments/acknowledge/{id}' => [ItemAssignmentController::class, 'acknowledgeItem'], // Acknowledge receipt of an item
    'pending-assignments' => [ItemAssignmentController::class, 'getPendingAssignments'],  // Get pending assignments for a user

        // Item Returns Routes
    'item-returns' => [ItemReturnController::class, 'viewReturns'], // View all item returns
    'item-returns/recordform' => [ItemReturnController::class, 'showReturnForm'],
    'item-returns/record' => [ItemReturnController::class, 'recordReturn'],// Add a new return
    'item-returns/search' => [ItemReturnController::class, 'viewReturns'], // Search for returns
    'item-returns/update/{id}' => [ItemReturnController::class, 'handlePostRequest'], // Update an existing return
    'item-returns/delete' => [ItemReturnController::class, 'deletePendingReturn'], // Delete a return
    'item-returns/approve/{id}' => [ItemReturnController::class, 'approveReturn'], // Approve a return
    'item-returns/acknowledge/{id}' => [ItemReturnController::class, 'acknowledgeReturn'], // Acknowledge a return
    'item-returns/pending' => [ItemReturnController::class, 'getPendingReturns'], // Get pending returns
    'item-returns/approved' => [ItemReturnController::class, 'getApprovedReturns'], // Get approved returns

    //assets
    'assets/instock' => [InventoryController::class, 'getInStockItems'], 
    'assets/inuse' => [InventoryController::class, 'getInUseItems'], 
 
    // Record Collections
    'collections/record' => [ItemReturnController::class, 'recordReturn'],
    // Collections Dropdown
    'collections' => [ItemReturnController::class, 'viewCollections'], 
    'collections/approveReturn' => [ItemReturnController::class, 'approveReturn'],
    'collections/lost' => [ItemReturnController::class, 'getLostItems'],
    'collections/repairs' => [ItemReturnController::class, 'getRepairItems'],
    'collections/updateRepairStatus' => [ItemReturnController::class, 'updateRepairStatus'],
    'collections/disposed' => [ItemReturnController::class, 'getDisposedItems'], 

    // Category routes-done
    'categories' => [CategoryController::class, 'index'],          // Display all categories
    'categories/add' => [CategoryController::class, 'add'],         // Add new category
    'categories/edit' => [CategoryController::class, 'edit'],       // Edit existing category
    'categories/delete' => [CategoryController::class, 'delete'],   // Delete category

    // Department routes - done
    'departments' => [DepartmentController::class, 'index'],          // Display all departments
    'departments/add' => [DepartmentController::class, 'store'],       // Add new department
    'departments/edit' => [DepartmentController::class, 'update'],     // Edit existing department
    'departments/delete' => [DepartmentController::class, 'destroy'],  // Delete department

    // Position routes - done
    'positions' => [PositionController::class, 'index'],           // Display all positions
    'positions/add' => [PositionController::class, 'add'],          // Add new position
    'positions/edit' => [PositionController::class, 'edit'],        // Edit existing position
    'positions/delete' => [PositionController::class, 'delete'],    // Delete position

        // Location routes - done
    'locations' => [LocationController::class, 'index'],            // Display all locations
    'locations/add' => [LocationController::class, 'add'],           // Add new location
    'locations/edit' => [LocationController::class, 'edit'],         // Edit existing location
    'locations/delete' => [LocationController::class, 'delete'],     // Delete location

    // Offices Routes - done
    'offices' => [OfficeController::class, 'index'],          // Display all offices
    'offices/add' => [OfficeController::class, 'add'],         // Add new office
    'offices/edit' => [OfficeController::class, 'edit'],       // Edit existing office
    'offices/delete' => [OfficeController::class, 'delete'],   // Delete office

    // Auth Users Routes
    'auth_users' => [AuthUserController::class, 'index'],          // Display all users
    'auth_users/add' => [AuthUserController::class, 'add'],         // Add new user
    'auth_users/edit' => [AuthUserController::class, 'edit'],       // Edit existing user
    'auth_users/delete' => [AuthUserController::class, 'delete'],   // Delete user
    'auth_users/fetch_locations' => [AuthUserController::class, 'fetchLocations'], // Fetch locations by office_id (AJAX)
    

];

return $routes;

