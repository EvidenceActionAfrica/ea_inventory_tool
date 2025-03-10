<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Evidence Action</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link href="<?php echo URL; ?>css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" crossorigin="anonymous"></script>

    <style>
        @font-face {
            font-family: ArchivoBlack;
            src: url("<?php echo URL;?>/fonts/ArchivoBlack-Regular.ttf");
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .top-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 2rem;
            background-color: #f4f4f4;
        }

        .bt-logout {
            background-color: #20253a;
            color: #fff;
            border: none;
            padding: .5rem 1rem;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
        }

        .nav-option {
            text-decoration: none;
            font-size: 15px;
            padding: .8rem 1rem;
            color: #20253a;
            font-family: ArchivoBlack;
        }

        .nav-option:hover,
        .dropdown-item:hover {
            background-color: #ecfafb;
        }

        .active {
            background-color: #ecfafb;
            border-bottom: 3px solid #05545a;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            z-index: 1000;
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
    </style>
</head>

<body>

<?php
  $current_page = basename($_SERVER['REQUEST_URI']);
?>

<!-- Top Navigation -->
<div class="top-nav">

    <!-- Logo -->
    <a href="<?php echo URL; ?>home">
        <img src="<?php echo URL; ?>img/ea_logo.png" alt="Evidence Action Logo" style="width: 150px; height: auto;">
    </a>

    <!-- Navigation Links -->
    <div>
        <a href="<?php echo URL; ?>pending-assignments" class="nav-option <?php echo $current_page == 'pending-assignments' ? 'active' : ''; ?>">
            PENDING ASSIGNMENTS
        </a>

        <a href="<?php echo URL; ?>item-returns" class="nav-option <?php echo $current_page == 'item-returns' ? 'active' : ''; ?>">
            RETURN ITEM
        </a>
    </div>

    <!-- Logout Button -->
    <a href="<?php echo URL; ?>home/logout" class="bt-logout">LOGOUT</a>

</div>

</body>
</html>
