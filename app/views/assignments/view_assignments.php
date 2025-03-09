<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../authusers/auth.php";

// Call role check
checkRoles(['QAQC', 'IT', 'MLE', 'super_admin']);
?>

<?php include(__DIR__ . "/../../../public/includes/header.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Assignments</title>
    <link href="<?php echo URL; ?>css/tables.css" rel="stylesheet">
</head>
<body>

    <h2>Item Assignments</h2>

    <!-- Success & Error Messages -->
    <?php if (isset($_GET['success'])): ?>
        <p class="success-message"><?= htmlspecialchars($_GET['success']); ?></p>
    <?php elseif (isset($_GET['error'])): ?>
        <p class="error-message"><?= htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <!-- Top Bar (Search + Add Button + Filter) -->
    <div class="top-bar">
        <!-- Search Form -->
        <form method="GET" action="<?php echo URL; ?>item-assignments/search" class="search-form">
            <input type="text" name="search" placeholder="Search by Name, Tag Number, or Serial Number" 
                value="<?= htmlspecialchars($search ?? ''); ?>">
            
            <!-- Acknowledgment Filter -->
            <select name="acknowledged">
                <option value="">All</option>
                <option value="pending" <?= $acknowledged === 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="acknowledged" <?= $acknowledged === 'acknowledged' ? 'selected' : ''; ?>>Acknowledged</option>
            </select>

            <button type="submit">Search</button>

            <?php if (!empty($search) || !empty($acknowledged)): ?>
                <a href="<?php echo URL; ?>item-assignments/search" class="reset-search">Reset</a>
            <?php endif; ?>
        </form>

        <!-- Assign New Item Button -->
        <form action="<?php echo URL; ?>item-assignments/showadd" method="GET">
            <button type="submit" class="add-btn">Assign New Item</button>
        </form>
    </div>

    <!-- Assignments Table -->
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Location</th>
                <th>Item</th>
                <th>Serial Number</th>
                <th>Date Assigned</th>
                <th>Managed By</th>
                <th>Acknowledgment Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($assignments)): ?>
                <?php foreach ($assignments as $assignment): ?>
                    <tr>
                        <td><?= htmlspecialchars($assignment['user_name']); ?></td>
                        <td><?= htmlspecialchars($assignment['email']); ?></td>
                        <td><?= htmlspecialchars($assignment['department'] . ' - ' . $assignment['position']); ?></td>
                        <td><?= htmlspecialchars($assignment['location'] ?? 'N/A'); ?> - <?= htmlspecialchars($assignment['office'] ?? 'N/A'); ?></td>
                        <td><?= htmlspecialchars($assignment['category'] ?? 'N/A'); ?>: <?= htmlspecialchars($assignment['description'] ?? 'N/A'); ?></td>
                        <td><?= htmlspecialchars($assignment['serial_number']); ?></td>
                        <td><?= htmlspecialchars($assignment['date_assigned']); ?></td>
                        <td><?= htmlspecialchars($assignment['managed_by']); ?></td>
                        <td>
                            <?php if ($assignment['acknowledged'] === 'acknowledged'): ?>
                                <span class="status-acknowledged">Acknowledged</span>
                            <?php else: ?>
                                <span class="status-pending">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td>
                                                <!-- Delete Form -->
                                                <form action="<?php echo URL; ?>item-assignments/delete" method="POST" 
                                                    onsubmit="return confirm('Are you sure you want to delete this assignment?');" style="display:inline;">
                                                    <input type="hidden" name="id" value="<?= $assignment['id']; ?>">
                                                    <button type="submit" name="delete" class="delete-btn">Delete</button>
                                                </form>
                    </td>

                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10">No item assignments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
