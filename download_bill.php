<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) die('Access denied');
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

// Only allow access to own order
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order) die('Order not found.');

// Get user info
$userStmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$userStmt->execute([$_SESSION['user_id']]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

// Get order items
$itemsStmt = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$itemsStmt->execute([$order_id]);
$items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

// Company logo and info (customize these below!)
$logo_url = "https://cdn-icons-png.flaticon.com/512/726/726448.png"; // Example: update with your real logo
$store_name = "Furniture Store";
$store_address = "123 Furniture Lane, Metro City, XX";
$store_phone = "(555) 123-4567";
$store_email = "info@furniturestore.com";
$current_date = date('l, F j, Y, g:i A');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Bill #<?php echo $order['id']; ?> | Furniture Store</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #f8fafc;
            color: #222b37;
            margin: 0;
            padding: 0;
        }
        .invoice-wrapper {
            max-width: 730px;
            margin: 36px auto 24px auto;
            background: #fff;
            border-radius: 18px;
            padding: 2.6em 2.5em 2.3em 2.5em;
            box-shadow: 0 9px 60px #133f8881;
            position: relative;
        }
        .inv-header {
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 2px solid #e6eefd;
            padding-bottom: 1.3em;
            margin-bottom: 1.8em;
        }
        .inv-logo {
            display: flex; align-items: center; gap: 1.07em;
        }
        .inv-logo img {
            width: 54px; height: 54px; border-radius: 14px;
            box-shadow: 0 3px 14px #247bc227;
        }
        .store-meta {
            border-left: 3px solid #eec969;
            padding-left: 1.3em;
        }
        .store-meta h3 {
            font-size: 1.38em;
            color: #2c3f7e;
            margin: 0 0 .10em 0;
            letter-spacing: .04em;
            font-family: 'Merriweather', serif;
        }
        .store-meta p {
            font-size: 1em;
            color: #495464;
            margin: 0.09em 0;
        }
        .inv-main-title {
            font-size: 2.15em;
            margin: .8em 0 0.45em;
            color: #264478;
            font-family: 'Merriweather', serif;
            letter-spacing: .04em;
        }
        .inv-flex {
            display: flex; justify-content: space-between; gap: 2.8em;
            flex-wrap: wrap;
        }
        .bill-section {
            min-width: 240px;
            margin-bottom: 2.2em;
        }
        .bill-label {
            color: #bda23c; letter-spacing: .01em; font-weight: 700; font-size: 1.09em;
            display: block; margin-bottom: .4em;
        }
        .recip-info {
            margin-bottom: 2em; font-size: 1.11em; color: #223;
        }
        .bill-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: .85em;
        }
        .bill-table th, .bill-table td {
            border-bottom: 1.4px solid #e8edf5;
            padding: .80em 1em;
        }
        .bill-table th {
            background: #f6f8fc;
            color: #3e588a;
            font-family: 'Merriweather', serif;
            font-size: 1.07em;
            font-weight: 700;
        }
        .bill-table td {
            background: none;
            color: #29345b;
            font-size: 1.07em;
        }
        .bill-table .center { text-align: center; }
        .bill-table .right { text-align: right; }
        .bill-total-row td {
            font-weight: bold;
            font-size: 1.13em;
            color: #1652b8;
            border-top: 2px solid #e7eaf4;
        }
        .bill-status {
            display: inline-block;
            padding: .25em 1.2em;
            font-size: 1.02em;
            border-radius: 20px;
            background: #f9f9cd;
            color: #9e8902;
            font-weight: bold;
            letter-spacing: 0.01em;
            margin-right: 8px;
        }
        .bill-status.shipped { background: #dcf8e5; color: #1a9248; }
        .bill-status['out for delivery'] { background: #e3eafe; color: #305fff;}
        .bill-status.delivered { background: #dcebfb; color: #2363a2;}
        .bill-status.pending { background: #f9f9cd; color: #9e8902;}
        .bill-status.cancelled { background: #f5cccc; color: #bd2034;}
        .inv-meta {
            color: #5e6d95;
            font-size: 1em;
            margin-bottom: .5em;
            letter-spacing:.01em;
        }
        .print-btn {
            margin: 2.3em 0 0 0;
            background: linear-gradient(93deg,#1464b3 80%,#2483d9 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 12px 2em;
            font-weight: 700;
            font-size: 1.08em;
            cursor: pointer;
            transition: background .25s;
            box-shadow:0 2px 11px #2a6ce221;
        }
        .print-btn:hover { background: #16438e; }
        @media (max-width:650px) {
            .invoice-wrapper { padding: 1.25em .5em; }
            .inv-header, .inv-flex { flex-direction: column; gap:.9em;}
        }
    </style>
</head>
<body>
<div class="invoice-wrapper">
    <div class="inv-header">
        <div class="inv-logo">
            <img src="<?php echo htmlspecialchars($logo_url); ?>" alt="Company Logo">
            <div class="store-meta">
                <h3><?php echo htmlspecialchars($store_name); ?></h3>
                <p><?php echo htmlspecialchars($store_address); ?></p>
                <p><?php echo htmlspecialchars($store_email); ?> | <?php echo htmlspecialchars($store_phone); ?></p>
            </div>
        </div>
        <div class="inv-meta">
            <b>Bill #<?php echo $order['id']; ?></b><br>
            Date: <?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?><br>
            <span style="font-size:.97em;">Generated: <?php echo $current_date ?></span>
        </div>
    </div>
    <div class="inv-main-title">Order Invoice</div>
    <div class="inv-flex">
        <div class="bill-section">
            <span class="bill-label">Billed To:</span>
            <div class="recip-info">
                <?php echo htmlspecialchars($user['full_name'] ?? $user['username']); ?><br>
                <?php
                foreach (['address','city','state','zip'] as $field)
                    if (!empty($user[$field])) echo htmlspecialchars($user[$field]) . '<br>';
                if (!empty($user['phone'])) echo "Phone: " . htmlspecialchars($user['phone']) . '<br>';
                ?>
            </div>
            <span class="bill-label">Status:</span>
            <span class="bill-status <?php echo str_replace(' ','-', strtolower($order['status'])); ?>">
                 <?php echo htmlspecialchars(ucwords($order['status'])); ?>
            </span>
        </div>
        <div class="bill-section">
            <span class="bill-label">Payment Method:</span>
            <div class="recip-info">Online / Card (Demo)</div>
            <span class="bill-label">Order Date:</span>
            <div class="recip-info"><?php echo date('F j, Y, g:i A', strtotime($order['created_at'])); ?></div>
        </div>
    </div>
    <table class="bill-table">
        <thead>
            <tr>
                <th>Product</th>
                <th class="center">QTY</th>
                <th class="right">Unit Price</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php $total = 0; foreach ($items as $item): $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td class="center"><?php echo (int)$item['quantity']; ?></td>
                <td class="right">$<?php echo number_format($item['price'], 2); ?></td>
                <td class="right">$<?php echo number_format($subtotal, 2); ?></td>
            </tr>
        <?php endforeach; ?>
            <tr class="bill-total-row">
                <td colspan="3" class="right">Total</td>
                <td class="right">$<?php echo number_format($total, 2); ?></td>
            </tr>
        </tbody>
    </table>
    <button class="print-btn" onclick="window.print()"><i class="fa fa-print"></i> Print / Save as PDF</button>
    <div style="text-align:center; margin-top:2.6em; color:#8bb8e6; font-size:.97em;"><b>Thank you for shopping at <?php echo $store_name ?>!</b></div>
</div>
</body>
</html>
