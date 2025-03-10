
<?php
require_once __DIR__ . "/../authusers/auth.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use App\Controllers\ItemAssignmentController;

$controller = new ItemAssignmentController();
$user_id = $_SESSION['user']['id'] ?? null;

// Handle item acknowledgment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acknowledge'])) {
    $assignment_id = $_POST['assignment_id'] ?? null;
    if ($assignment_id && is_numeric($assignment_id)) {
        $controller->acknowledgeItem($assignment_id);
        header('Location: /ea_inventory_tool/public/pending-assignments?message=Acknowledged');
        exit(); // Stop further execution
    }
}

// Fetch pending assignments AFTER processing form submission
$pendingAssignments = $controller->getPendingAssignments($user_id);

if ($pendingAssignments === null) {
    die("Error fetching assignments.");
}
?>

<?php include(__DIR__ . "/../../../public/includes/header.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Assignments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo URL; ?>css/tables.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Pending Assignments</h2>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-info"><?= htmlspecialchars($_GET['message']) ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Description</th>
                    <th>Serial Number</th>
                    <th>Date Assigned</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pendingAssignments)): ?>
                    <?php foreach ($pendingAssignments as $assignment): ?>
                        <tr>
                            <td><?= htmlspecialchars($assignment['user_name']); ?></td>
                            <td><?= htmlspecialchars($assignment['email']); ?></td>
                            <td><?= htmlspecialchars($assignment['description']); ?></td>
                            <td><?= htmlspecialchars($assignment['serial_number']); ?></td>
                            <td><?= htmlspecialchars($assignment['date_assigned']); ?></td>
                            <td><?= htmlspecialchars($assignment['acknowledged']); ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="assignment_id" value="<?= $assignment['id']; ?>">
                                    <button type="submit" name="acknowledge" class="btn btn-primary btn-sm">Acknowledge</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7">No pending assignments found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

