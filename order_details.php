<div class="site-bg-overlay"></div>
<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order) {
    echo "<div class='container'><div class='message error'>Order not found.</div></div>";
    include 'includes/footer.php';
    exit;
}

$sql = "SELECT oi.quantity, oi.price, p.name 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        WHERE oi.order_id = ?";
$itemStmt = $pdo->prepare($sql);
$itemStmt->execute([$order_id]);
$items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container">
    <h1>Order #<?php echo $order_id; ?> Details</h1>
    <p><strong>Date:</strong> <?php echo htmlspecialchars($order['created_at']) ?><br>
       <strong>Status:</strong> <span class="order-status status-<?php echo strtolower($order['status']); ?>"><?php echo $order['status']; ?></span><br>
       <strong>Total:</strong> $<?php echo number_format($order['total_amount'], 2); ?>
    </p>
    <?php if (empty($items)): ?>
        <div class="message">No items found for this order.</div>
    <?php else: ?>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th>QTY</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo (int)$item['quantity']; ?></td>
                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <div style="margin-top:2em;">
        <a href="orders.php" class="btn btn-secondary">&larr; Back to Orders</a>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
