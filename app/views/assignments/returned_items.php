<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../authusers/auth.php";

if (!isset($_SESSION['user'])) {
    echo "❌ No user session found. Debugging session data:<br>";
    print_r($_SESSION);
    exit();
}

$user = $_SESSION['user'];


// Check roles
if (!function_exists('checkRoles')) {
    function checkRoles(array $allowedRoles) {
        global $user;

        if (!isset($user['role'])) {
            exit('❌ Access Denied: No user role set.');
        }

        $userRole = trim($user['role']);
        if (!in_array($userRole, $allowedRoles, true)) {
            exit('❌ Access Denied: Unauthorized role.');
        }
    }
}

checkRoles(['QAQC', 'IT', 'MLE', 'super_admin']);

$user_id = $user['id'] ?? null;

if (!$user_id) {
    die('❌ Error: User ID not found in session.');
}

?>

<?php include(__DIR__ . "/../../../public/includes/header.php"); ?>

<?php

use App\Models\ItemAssignment;
use App\Controllers\ItemReturnController;

// Initialize models
$itemAssignment = new ItemAssignment();
$controller = new ItemReturnController();

// Fetch assignments and returns
$assignments = $itemAssignment->getUserAssignments($user_id);
$user_returns = $controller->showUserReturns();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returned Items</title>
    <link href="<?php echo URL; ?>css/tables.css" rel="stylesheet">
</head>
<body>

<?php if (isset($_GET['success'])): ?>
    <p class="success-message"><?= htmlspecialchars($_GET['success']); ?></p>
<?php elseif (isset($_GET['error'])): ?>
    <p class="error-message"><?= htmlspecialchars($_GET['error']); ?></p>
<?php endif; ?>
<div class="container mt-5">
        <h2 style="text-decoration: underline;">Your Assigned Items</h2>

            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Serial Number</th>
                        <th>Tag Number</th>
                        <th>Date Assigned</th>
                        <th>Managed By</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($assignments)): ?>
                    <?php foreach ($assignments as $assignment): ?>
                        <tr>
                            <td><?php echo $assignment['category']; ?></td>
                            <td><?php echo $assignment['description']; ?></td>
                            <td><?php echo $assignment['serial_number']; ?></td>
                            <td><?php echo $assignment['tag_number']; ?></td>
                            <td><?php echo $assignment['date_assigned']; ?></td>
                            <td><?php echo $assignment['managed_by']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You don't have any assigned items.</p>
        <?php endif; ?>
    </div>

<h2 style="text-decoration: underline;">Returned Items</h2>
<div class="row">
    <div class="col-md-10">
        <!-- Search Bar -->
    </div>
    <div class="col-md-2">
        <!-- Record Return Button -->
        <form action="<?= URL; ?>item-returns/recordform" method="post">
    <button type="submit" class="add-btn">Record Return</button>
</form>

    </div>
</div>
</div>
<div class="container mt-5">
    <!-- Returned Items Table -->
<table class="styled-table">
    <thead>
        <tr>
            <th>Item Name</th>
            <th>Serial Number</th>
            <th>Received By</th>
            <th>Return Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($returns)): ?>
    <?php foreach ($returns as $return): ?>
        <tr>
            <td><?= htmlspecialchars($return['description']); ?></td>
            <td><?= htmlspecialchars($return['serial_number']); ?></td>
            <td><?= htmlspecialchars($return['received_by']); ?></td>
            <td><?= htmlspecialchars($return['return_date']); ?></td>
            <td><?= htmlspecialchars($return['status']); ?></td>
            <td>
                <?php if ($return['status'] === 'pending'): ?>
                    <a href="<?= URL; ?>item-returns/delete?id=<?= $return['id']; ?>" 
                    onclick="return confirm('Are you sure you want to delete this pending return?');">
                    Delete
                    </a>
                <?php else: ?>
                    N/A
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="6">No returned items found.</td>
    </tr>
<?php endif; ?>

    </tbody>
</table>

</div>

</body>
</html>
