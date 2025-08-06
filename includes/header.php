<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include __DIR__ . '/db.php';

function get_cart_item_count() {
    $cart_count = 0;
    if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $cart_count += (int)$item['quantity'];
        }
    }
    return $cart_count;
}
$cart_qty = get_cart_item_count();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furniture Store</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<header>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="index.php">
                    <i class="fa-solid fa-couch" style="color:#ebc849; font-size:1.42em; margin-right:.38em; vertical-align:middle;"></i>
                    FurnitureStore
                </a>
            </div>
            <div class="nav-spacer"></div>
           <ul class="nav-menu">
    <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
    <li><a href="products.php"><i class="fa-solid fa-table-list"></i> Products</a></li>
    <?php if (isset($_SESSION['user_id'])): ?>
        <li><a href="orders.php"><i class="fa fa-box"></i> Orders</a></li>
        <li>
            <a href="cart.php" class="nav-cart-link">
                <i class="fa fa-shopping-cart"></i>
                <span>Cart</span>
                <span id="cart-count"><?php echo $cart_qty; ?></span>
            </a>
        </li>
        <li class="profile-menu-container">
            <button id="profileMenuBtn" class="profile-menu-button" title="Profile">
                <i class="fa-solid fa-user"></i>
            </button>
            <div class="profile-dropdown" id="profileDropdown">
                <div style="padding:.6em 1.7em; color:#666; font-size:.92em;">
                    <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>
                </div>
                <a href="profile.php"><i class="fa fa-id-card"></i> My Profile</a>
                <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
            </div>
        </li>
    <?php else: ?>
        <li><a href="login.php"><i class="fa-solid fa-right-to-bracket"></i> Login</a></li>
    <?php endif; ?>
</ul>

        </div>
    </nav>
</header>
<main>
