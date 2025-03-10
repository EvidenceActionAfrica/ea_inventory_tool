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

    <style>
        @font-face {
            font-family: ArchivoBlack;
            src: url("<?php echo URL;?>/fonts/ArchivoBlack-Regular.ttf");
        }

        /* Navbar styles */
        .top-nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
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

        .dfaicjcsbg2 {
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            gap: 2rem;
        }

        .nav-option {
            text-align: center;
            text-decoration: none;
            font-size: 15px;
            padding: .8rem .6rem;
            color: #20253a;
            font-family: ArchivoBlack;
            margin-left: 1rem;
        }

        .nav-option:hover, .dropdown-item:hover {
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

        .dropdown-toggle {
            cursor: pointer;
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

        .dropdown .active {
            font-weight: bold;
        }

        /* Content styles */
        .main-content {
            margin-top: 80px; /* Adjust for fixed navbar */
            padding: 20px;
        }
    </style>
</head>
<body>

<?php
  $current_page = basename($_SERVER['REQUEST_URI']);
  $assets_pages = ['instock', 'inuse'];
  $collections_pages = ['pending', 'lost', 'repairs', 'disposed'];
  $config_pages = ['inventory', 'categories'];
?>

<div class="top-nav dfaicjcsbg2">
  <a href="<?php echo URL; ?>home">
    <img src="<?php echo URL; ?>img/ea_logo.png" alt="logo" style="width: 150px; height: auto;">
  </a>

  <div>
    <a href="<?php echo URL; ?>pending-assignments" class="nav-option <?php echo $current_page == 'pending-assignments' ? 'active' : ''; ?>">PENDING ASSIGNMENTS</a>
    <a href="<?php echo URL; ?>item-assignments" class="nav-option <?php echo $current_page == 'item-assignments' ? 'active' : ''; ?>">ASSIGNMENTS</a>
    <a href="<?php echo URL; ?>item-returns" class="nav-option <?php echo $current_page == 'item-returns' ? 'active' : ''; ?>">RETURN ITEM</a>

    <div class="dropdown">
      <a href="#" class="nav-option dropdown-toggle <?php echo in_array($current_page, $assets_pages) ? 'active' : ''; ?>">ASSETS</a>
      <div class="dropdown-menu">
        <a href="<?php echo URL; ?>assets/instock" class="dropdown-item <?php echo $current_page == 'instock' ? 'active' : ''; ?>">In-Stock</a>
        <a href="<?php echo URL; ?>assets/inuse" class="dropdown-item <?php echo $current_page == 'inuse' ? 'active' : ''; ?>">In-Use</a>
      </div>
    </div>

    <div class="dropdown">
      <a href="#" class="nav-option dropdown-toggle <?php echo in_array($current_page, $collections_pages) ? 'active' : ''; ?>">COLLECTIONS</a>
      <div class="dropdown-menu">
        <a href="<?php echo URL; ?>item-returns/pending" class="dropdown-item <?php echo $current_page == 'pending' ? 'active' : ''; ?>">Pending Approvals</a>
        <a href="<?php echo URL; ?>collections/lost" class="dropdown-item <?php echo $current_page == 'lost' ? 'active' : ''; ?>">Lost Inventory</a>
        <a href="<?php echo URL; ?>collections/repairs" class="dropdown-item <?php echo $current_page == 'repairs' ? 'active' : ''; ?>">Repairs</a>
        <a href="<?php echo URL; ?>collections/disposed" class="dropdown-item <?php echo $current_page == 'disposed' ? 'active' : ''; ?>">Disposed</a>
      </div>
    </div>

    <div class="dropdown">
      <a href="#" class="nav-option dropdown-toggle <?php echo in_array($current_page, $config_pages) ? 'active' : ''; ?>">CONFIGURATIONS</a>
      <div class="dropdown-menu">
        <a href="<?php echo URL; ?>inventory" class="dropdown-item <?php echo $current_page == 'inventory' ? 'active' : ''; ?>">Inventory</a>
        <a href="<?php echo URL; ?>categories" class="dropdown-item <?php echo $current_page == 'categories' ? 'active' : ''; ?>">Categories</a>
      </div>
    </div>
  </div>

  <a href="<?php echo URL; ?>home/logout" class="bt-logout">LOGOUT</a>
</div>

<!-- Main content section -->
<div class="main-content">
  <!-- <h2>Welcome to Evidence Action Inventory System</h2>
  <p>This is the main content section. Replace this text with your actual content.</p> -->
</div>

</body>
</html>
