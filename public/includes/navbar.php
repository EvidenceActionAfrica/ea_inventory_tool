<?php
require_once __DIR__ . "/../../app/config/config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Bar</title>
    
    <!-- Bootstrap CSS (for dropdown functionality) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style> 
        /* Navigation Bar Styling */
        .navbar {
            background-color: #007bff;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            position: fixed;
            top: 0;
            bottom: 5px;
            width: 100%;
            height: 50px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        .nav-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 25px; /* Adds space between items */
        }

        .nav-links li {
            position: relative;
        }

        .nav-links a {
            text-decoration: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 8px 15px;
            transition: background 0.3s, color 0.3s;
            border-radius: 5px;
        }

        .nav-links a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Dropdown Styling */
        .dropdown-menu {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dropdown-menu a {
            color: #333;
            font-size: 14px;
            padding: 10px 15px;
            display: block;
            transition: background 0.3s;
        }

        .dropdown-menu a:hover {
            background-color: #f8f9fa;
        }

    </style>
</head>
<body>

    <nav class="navbar">
        <a href="view_inventory.php" class="logo">Inventory System</a>
        <ul class="nav-links">
        <li><a href="pending_assignments.php">Pending Assignments</a></li>
        <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="view_collections.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Assets
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="inventory_instock.php">In Stock</a></li>
                    <li><a class="dropdown-item" href="inventory_inuse.php">In Use</a></li>
                </ul>
            </li>

            <li><a href="view_assignments.php">Assignments</a></li>
            <li><a href="returned_items.php">Record Collections</a></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="view_collections.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Collections
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="pending.php">Pending</a></li>
                    <li><a class="dropdown-item" href="inventory_lost.php">Lost</a></li>
                    <li><a class="dropdown-item" href="repairs.php">Repairs</a></li>
                    <li><a class="dropdown-item" href="disposed.php">Disposed</a></li>
                </ul>
            </li>

            <!-- Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Configurations
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="view_inventory.php">Inventory</a></li>
                    <li><a class="dropdown-item" href="categories.php">Categories</a></li>
                    <li><a class="dropdown-item" href="user_profiles.php">Profiles</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Admin
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="auth_users.php">Users</a></li>
                    <li><a class="dropdown-item" href="position_view.php">Positions</a></li>
                    <li><a class="dropdown-item" href="department_view.php">Departments</a></li>
                    <li><a class="dropdown-item" href="office_view.php">Office</a></li>
                    <li><a class="dropdown-item" href="location_view.php">Location</a></li>
                </ul>
            </li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Bootstrap JavaScript (for dropdown functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
