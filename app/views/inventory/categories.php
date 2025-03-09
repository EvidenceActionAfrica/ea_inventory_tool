<?php
require_once __DIR__ . "/../authusers/auth.php";

checkRoles(['IT', 'super_admin']); 
?>
<?php 
include(__DIR__ . "/../../../public/includes/header.php"); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
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
    <h4>Assets Catalogue</h4>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Table -->
        <div class="col-md-8 table-container">
            <table >
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= htmlspecialchars($category['category_name']) ?></td>
                            <td><?= htmlspecialchars($category['description']) ?></td>
                            <td>
                                <!-- Edit Button -->
                                <button class="btn btn-warning btn-sm"
                                        onclick="editCategory(<?= $category['id'] ?>, '<?= htmlspecialchars($category['category_name']) ?>', '<?= htmlspecialchars($category['description']) ?>')">
                                    Edit
                                </button>

                                <!-- Delete Button -->
                                <a href="<?= URL; ?>categories/delete?delete=<?= $category['id'] ?>" 
                                class="btn btn-danger btn-sm" 
                                onclick="return confirm('Are you sure you want to delete this category?');">
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
                <h5 class="text-center">Add Item</h5>
                <form method="POST" action="<?= URL; ?>categories/add">
                    <div class="mb-3">
                        <label>Category Name:</label>
                        <input type="text" name="category_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description:</label>
                        <input type="text" name="description" class="form-control" required>
                    </div>
                    <button type="submit" name="add" class="btn btn-primary w-100">Add Category</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <form method="POST" action="<?= URL; ?>categories/edit">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label>Category Name:</label>
                            <input type="text" name="category_name" id="edit_category_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Description:</label>
                            <input type="text" name="description" id="edit_description" class="form-control" required>
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
function editCategory(id, name, desc) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_category_name').value = name;
    document.getElementById('edit_description').value = desc;
    var myModal = new bootstrap.Modal(document.getElementById('editModal'));
    myModal.show();
}
</script>

</body>
</html>
