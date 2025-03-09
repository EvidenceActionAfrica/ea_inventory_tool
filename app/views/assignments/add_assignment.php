<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../authusers/auth.php";
checkRoles(['QAQC', 'IT', 'super_admin']);
 use App\Models\AuthUser;
 use App\Models\Inventory;
 use App\Models\ItemAssignment;

$itemAssignment = new ItemAssignment();
$authUser = new AuthUser();
$inventory = new Inventory();

// Fetch unassigned items and users
$unassignedItems = $itemAssignment->getUnassignedItems();
$users = $authUser->getAllProfiles();

// Search logic
$searchQuery = $_GET['search'] ?? null;
$assignments = $itemAssignment->getAllAssignments($searchQuery);
?>

<?php include(__DIR__ . "/../../../public/includes/header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Items</title>
    <link href="<?php echo URL; ?>css/tables.css" rel="stylesheet">
</head>
<body>

<div class="form-container">
    <h2>Assign Items to Users</h2>


    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>

    <!-- Assignment form -->
    <form action="<?= URL ?>item-assignments/store" method="POST">
        <div id="item-container">
            <div class="form-group item-group">
                <label for="inventory_id[]">Select Item:</label>
                <select name="inventory_id[]" required>
                    <option value="">Choose an item</option>
                    <?php foreach ($unassignedItems as $item): ?>
                        <option value="<?= $item['id']; ?>"><?= htmlspecialchars($item['description']); ?> (<?= htmlspecialchars($item['serial_number']); ?>)</option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="remove-item-btn" onclick="removeItem(this)" style="display: none;">Remove</button>
            </div>
        </div>

        <button type="button" id="add-item-btn">Add Another Item</button>

        <div class="form-group">
            <label for="user_id">Select User:</label>
            <select name="user_id" required>
                <option value="">Select User</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="date_assigned">Date Assigned:</label>
            <input type="date" id="date_assigned" name="date_assigned" required>
        </div>

        <div class="form-group">
            <label for="managed_by">Managed By:</label>
            <input type="text" id="managed_by" name="managed_by" required>
        </div>

        <button type="submit" name="add_assignment" class="submit-btn">Assign Items</button>
    </form>

    