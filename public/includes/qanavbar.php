<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Evidence Action</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link href="<?php echo URL; ?>css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" />
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <style>
      @font-face {
        font-family: ArchivoBlack;
        src: url("<?php echo URL;?>/fonts/ArchivoBlack-Regular.ttf");
      }

      .bt-logout {
        background-color: #20253a;
        color: #fff;
        border-style: none;
        padding: .5rem 1rem;
        font-size: 16px;
        border-radius: 5px;
        margin-right: 2rem;
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
        margin-left: 1rem;
        font-family: ArchivoBlack;
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
    </style>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>
<body>

<?php
  $current_page = basename($_SERVER['REQUEST_URI']);
  $assets_pages = ['instock', 'inuse'];
  $collections_pages = ['pending', 'lost', 'repairs', 'disposed'];
  $config_pages = ['inventory', 'categories'];
  $admin_pages = ['auth_users', 'offices', 'locations', 'positions', 'departments'];
?>

<div class="top-nav dfaicjcsbg2">

  <a href="<?php echo URL; ?>home">
    <img src="<?php echo URL; ?>img/ea_logo.png" alt="logo" style="padding: 10px; width: 150px; height: auto;">
  </a>

  <div>
    <!-- Main links -->
    <a href="<?php echo URL; ?>pending-assignments" class="nav-option <?php echo $current_page == 'pending-assignments' ? 'active' : ''; ?>">PENDING ASSIGNMENTS</a>

    <a href="<?php echo URL; ?>item-assignments" class="nav-option <?php echo $current_page == 'item-assignments' ? 'active' : ''; ?>">ASSIGNMENTS</a>

    <a href="<?php echo URL; ?>item-returns" class="nav-option <?php echo $current_page == 'item-returns' ? 'active' : ''; ?>">RETURN ITEM</a>

    <!-- Dropdown menus -->
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


  <div class="dfaicjcsbg2">
  <a href="<?php echo URL; ?>home/logout" class="bt-logout">LOGOUT</a>
  </div>

</div>

</body>
</html>
