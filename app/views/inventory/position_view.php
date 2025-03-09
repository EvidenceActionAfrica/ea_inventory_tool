<?php
require_once __DIR__ . "/../authusers/auth.php";

checkRoles(['super_admin']); 
?>
<?php include(__DIR__ . "/../../../public/includes/header.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Positions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo URL; ?>css/tables.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 80px; /* To accommodate fixed navbar */
        }
        .table-container {
            width: 70%;
        }
        .form-container {
            width: 30%;
        }
        .card {
            padding: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <h4>Manage Positions</h4>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Table -->
        <div class="col-md-8 table-container">
            <table >
                <thead >
                    <tr>
                        <th>Position Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($positions as $position): ?>
                        <tr>
                            <td><?= htmlspecialchars($position['name']) ?></td>
                            <td>
                                <!-- Edit Button -->
                                <button class="btn btn-warning btn-sm" onclick="editPosition(<?= $position['id'] ?>, '<?= htmlspecialchars($position['name']) ?>')">Edit</button>
                                
                                <!-- Delete Position Link -->
                                <a href="/ea_inventory_tool/public/positions/delete?delete=<?= $position['id'] ?>" 
                                class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this position?')">
                                Delete
                                </a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Form -->
        <div class="col-md-4 form-container">
            <div class="card">
                <h5 class="text-center">Add Position</h5>
                <form action="/ea_inventory_tool/public/positions/add" method="POST">
                    <div class="mb-3">
                        <label>Position Name:</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <button type="submit" name="add" class="btn btn-primary w-100">Add Position</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <form action="/ea_inventory_tool/public/positions/edit" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Position</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label>Position Name:</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function editPosition(id, name) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    var myModal = new bootstrap.Modal(document.getElementById('editModal'));
    myModal.show();
}
</script>

</body>
</html>
