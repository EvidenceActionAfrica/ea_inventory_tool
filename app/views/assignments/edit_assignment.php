<?php

// Display error or success messages
if (isset($errorMessage)) {
    echo "<p style='color: red;'>$errorMessage</p>";
}
?>
<?php
require_once __DIR__ . "/../authusers/auth.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
    <title>Edit Assignment</title>
    <link href="<?= htmlspecialchars(URL) ?>css/tables.css" rel="stylesheet">
</head>
<body>

    <div class="form-container">
        <h2>Edit Assignment</h2>

        <form action="<?= URL . 'item-assignments/updateAssignment'; ?>" method="POST">
            <input type="hidden" name="id" value="<?= $assignment['id']; ?>">

            <div class="form-group">
                <label for="inventory_id">Select Item:</label>
                <select name="inventory_id[]" required>
                    <option value="">Choose an item</option>
                    <?php foreach ($unassignedItems as $item): ?>
                        <option value="<?= $item['id']; ?>"><?= htmlspecialchars($item['description']); ?> (<?= htmlspecialchars($item['serial_number']); ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            
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
                <input type="date" id="date_assigned" name="date_assigned" value="<?= $assignment['date_assigned']; ?>" required>
            </div>

            <div class="form-group">
                <label for="managed_by">Managed By:</label>
                <input type="text" id="managed_by" name="managed_by" value="<?= htmlspecialchars($assignment['managed_by']); ?>" required>
            </div>

            <button type="submit" name="update_assignment" class="submit-btn">Update Assignment</button>
        </form>
    </div>

</body>
</html>
