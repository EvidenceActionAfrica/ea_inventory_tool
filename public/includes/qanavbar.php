<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Evidence Action</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link href="<?php echo URL; ?>css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" />
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <style>
        @font-face {
            font-family: ArchivoBlack;
            src: url("<?php echo URL; ?>/fonts/ArchivoBlack-Regular.ttf");
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .top-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 1rem 2rem;
            border-bottom: 2px solid #ccc;
        }

        .nav-option {
            text-decoration: none;
            font-size: 15px;
            padding: .8rem 1rem;
            color: #20253a;
            font-family: ArchivoBlack;
        }

        .nav-option:hover, .dropdown-item:hover {
            background-color: #ecfafb;
        }

        .active {
            background-color: #ecfafb;
            border-bottom: 3px solid #05545a;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center; /* Ensure all items align in a single row */
        }

        .dropdown {
            position: relative;
            display: flex;
            align-items: center; /* Align dropdown like other nav items */
        }

        .dropdown-toggle {
            text-decoration: none;
            font-size: 15px;
            padding: .8rem 1rem;
            color: #20253a;
            font-family: ArchivoBlack;
            cursor: pointer;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            min-width: 200px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000; /* Ensure dropdown is on top */
        }

        .dropdown-item {
            padding: 10px;
            font-size: 14px;
            color: #20253a;
            text-decoration: none;
            display: block;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .bt-logout {
            background-color: #20253a;
            color: #fff;
            border: none;
            padding: .5rem 1.5rem;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
        }
    </style>
</head>

<body>

<?php
  $current_page = basename($_SERVER['REQUEST_URI']);
  $assets_pages = ['instock', 'inuse'];
  $collections_pages = ['pending', 'lost', 'repairs', 'disposed'];
?>

<div class="top-nav">
    <!-- Logo -->
    <a href="<?php echo URL; ?>home">
        <img src="<?php echo URL; ?>img/ea_logo.png" alt="Evidence Action" style="width: 150px; height: auto;">
    </a>

    <!-- Navigation Links -->
    <div class="nav-links">
        <a href="<?php echo URL; ?>pending-assignments" class="nav-option <?php echo $current_page == 'pending-assignments' ? 'active' : ''; ?>">Pending Assignments</a>

        <a href="<?php echo URL; ?>item-assignments" class="nav-option <?php echo $current_page == 'item-assignments' ? 'active' : ''; ?>">Assignments</a>

        <a href="<?php echo URL; ?>item-returns" class="nav-option <?php echo $current_page == 'item-returns' ? 'active' : ''; ?>">Return Item</a>

        <!-- Assets Dropdown -->
        <div class="dropdown">
            <a href="#" class="nav-option dropdown-toggle <?php echo in_array($current_page, $assets_pages) ? 'active' : ''; ?>">Assets</a>
            <div class="dropdown-menu">
                <a href="<?php echo URL; ?>assets/instock" class="dropdown-item <?php echo $current_page == 'instock' ? 'active' : ''; ?>">In-Stock</a>
                <a href="<?php echo URL; ?>assets/inuse" class="dropdown-item <?php echo $current_page == 'inuse' ? 'active' : ''; ?>">In-Use</a>
            </div>
        </div>

        <!-- Collections Dropdown -->
        <div class="dropdown">
            <a href="#" class="nav-option dropdown-toggle <?php echo in_array($current_page, $collections_pages) ? 'active' : ''; ?>">Collections</a>
            <div class="dropdown-menu">
                <a href="<?php echo URL; ?>item-returns/pending" class="dropdown-item <?php echo $current_page == 'pending' ? 'active' : ''; ?>">Pending Approvals</a>
                <a href="<?php echo URL; ?>collections/lost" class="dropdown-item <?php echo $current_page == 'lost' ? 'active' : ''; ?>">Lost Inventory</a>
                <a href="<?php echo URL; ?>collections/repairs" class="dropdown-item <?php echo $current_page == 'repairs' ? 'active' : ''; ?>">Repairs</a>
                <a href="<?php echo URL; ?>collections/disposed" class="dropdown-item <?php echo $current_page == 'disposed' ? 'active' : ''; ?>">Disposed</a>
            </div>
        </div>
    </div>

    <!-- Logout Button -->
    <a href="<?php echo URL; ?>home/logout" class="bt-logout">Logout</a>
</div>

</body>
</html>
