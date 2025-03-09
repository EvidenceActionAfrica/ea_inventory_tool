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

// Query for assigned items
$query = "SELECT ia.id, u.name AS user_name, u.email, c.category_name AS category, 
                 i.description, i.serial_number, i.tag_number, ia.date_assigned, ia.managed_by
          FROM item_assignments ia
          JOIN auth_users u ON ia.user_id = u.id
          JOIN inventory i ON ia.inventory_id = i.id
          LEFT JOIN categories c ON i.category_id = c.id";

if (!empty($search)) {
    $query .= " WHERE i.serial_number LIKE :search OR i.tag_number LIKE :search";
}

$query .= " ORDER BY ia.date_assigned DESC";
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
    <title>In Use Items</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
    <link href="<?php echo URL; ?>css/tables.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-2">
        <h2 class="mb-3">Items Currently In Use</h2>

        <!-- Success/Error Messages -->
        <?php if (isset($_GET['success'])): ?>
            <p class="alert alert-success"><?= htmlspecialchars($_GET['success']); ?></p>
        <?php elseif (isset($_GET['error'])): ?>
            <p class="alert alert-danger"><?= htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <!-- Top Bar (Search) -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form method="GET" class="d-flex">
                <input type="text" name="search" placeholder="Search by Tag or Serial No." 
                       class="form-control me-2" value="<?= htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">Search</button>
                <?php if (!empty($search)): ?>
                    <a href="<?php echo URL; ?>assets/inuse" class="btn btn-secondary ms-2">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Items Table -->
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Serial Number</th>
                    <th>Tag Number</th>
                    <th>Date Assigned</th>
                    <th>Managed By</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['user_name']) ?></td>
                            <td><?= htmlspecialchars($item['email']) ?></td>
                            <td><?= htmlspecialchars($item['category']) ?></td>
                            <td><?= htmlspecialchars($item['description']) ?></td>
                            <td><?= htmlspecialchars($item['serial_number']) ?></td>
                            <td><?= htmlspecialchars($item['tag_number']) ?></td>
                            <td><?= htmlspecialchars($item['date_assigned']) ?></td>
                            <td><?= htmlspecialchars($item['managed_by']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No items found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
