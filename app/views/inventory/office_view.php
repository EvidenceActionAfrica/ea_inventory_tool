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
    <title>Manage Offices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo URL; ?>css/tables.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h4>Manage Offices</h4>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <table>
                    <thead>
                        <tr>
                            <th>Office Name</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($offices as $office): ?>
                            <tr>
                                <td><?= htmlspecialchars($office['name']) ?></td>
                                <td><?= htmlspecialchars($office['location_name']) ?></td>
                                <td>
                                    <a href="offices/delete?id=<?= $office['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <h5>Add Office</h5>
                <form action="offices/add" method="POST">
                    <div class="mb-3">
                        <label>Location:</label>
                        <select name="location_id" class="form-control" required>
                            <?php foreach ($locations as $location): ?>
                                <option value="<?= $location['id'] ?>"><?= htmlspecialchars($location['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Office Name:</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Office</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
