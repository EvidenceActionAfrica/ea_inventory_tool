<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../authusers/auth.php";

checkRoles(['QAQC', 'IT', 'super_admin']); 
?>
<?php include(__DIR__ . "/../../../public/includes/header.php"); ?>
<?php
use App\Config\Database;

$database = new Database();
$conn = $database->connect();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Base query: Only approved returns with item_state = 'damaged'
$query = "SELECT i.id, c.category_name AS category, i.description, 
                 i.serial_number, i.tag_number, i.acquisition_date, i.warranty_date,
                 ir.item_state, ir.status, ir.repair_status
          FROM item_returns ir
          INNER JOIN inventory i ON ir.item_id = i.id
          LEFT JOIN categories c ON i.category_id = c.id
          WHERE ir.status = 'approved'
          AND ir.item_state = 'damaged'";

if (!empty($search)) {
    $query .= " AND (i.serial_number LIKE :search OR i.tag_number LIKE :search)";
}

$query .= " ORDER BY i.acquisition_date DESC";
$stmt = $conn->prepare($query);

if (!empty($search)) {
    $searchParam = "%$search%";
    $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
}

$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Damaged Items</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
    <link href="<?php echo URL; ?>css/tables.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-2">
        <h2>Damaged Items</h2>

        <!-- Success/Error Messages -->
        <?php if (isset($_GET['success'])): ?>
            <p class="success-message"><?= htmlspecialchars($_GET['success']); ?></p>
        <?php elseif (isset($_GET['error'])): ?>
            <p class="error-message"><?= htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <!-- Top Bar (Search) -->
        <div class="top-bar d-flex justify-content-between align-items-center mb-3">
            <form method="GET" action="damaged_items.php" class="search-form d-flex">
                <input type="text" name="search" placeholder="Search by Tag or Serial No." 
                       class="form-control me-2" value="<?= htmlspecialchars($search); ?>">

                <button type="submit" class="btn btn-primary">Search</button>
                <?php if (!empty($search)): ?>
                    <a href="damaged_items.php" class="btn btn-secondary ms-2">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Inventory Table -->
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Serial Number</th>
                    <th>Tag Number</th>
                    <th>Repair Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['category']) ?></td>
                            <td><?= htmlspecialchars($item['description']) ?></td>
                            <td><?= htmlspecialchars($item['serial_number']) ?></td>
                            <td><?= htmlspecialchars($item['tag_number']) ?></td>
                            <td>
                                <?php
                                if ($item['repair_status']) {
                                    echo htmlspecialchars($item['repair_status']);
                                } else {
                                    echo "<span class='text-warning'>Pending</span>";
                                }
                                ?>
                            </td>
                            <td>
                                <?php if (!$item['repair_status']): ?>
                                    <form method="POST" action="<?= URL ?>collections/updateRepairStatus">
                                        <input type="hidden" name="item_id" value="<?= htmlspecialchars($item['id']); ?>">
                                        <select name="repair_status" class="form-select form-select-sm" required>
                                            <option value="">Select</option>
                                            <option value="Repairable">Repaired</option>
                                            <option value="Unrepairable">Unrepairable</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary mt-1">Update</button>
                                    </form>

                                <?php else: ?>
                                    <span class="text-muted">Updated</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No damaged items found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
