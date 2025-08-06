<?php 
include 'includes/db.php';

if (!isset($_GET['id'])) {
    die('Order ID required');
}

$order_id = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("
        SELECT o.*, u.username, u.email 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        WHERE o.id = ?
    ");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();
    
    if (!$order) {
        die('Order not found');
    }
    
    $stmt = $pdo->prepare("
        SELECT oi.*, p.name as product_name 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        WHERE oi.order_id = ?
    ");
    $stmt->execute([$order_id]);
    $items = $stmt->fetchAll();
    
} catch(PDOException $e) {
    die('Database error: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details #<?php echo $order['id']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .order-header { background: #f8f9fa; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table th, .items-table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        .items-table th { background: #f8f9fa; }
        .total { font-weight: bold; font-size: 1.2rem; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="order-header">
        <h1>Order #<?php echo $order['id']; ?></h1>
        <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
        <p><strong>Status:</strong> <?php echo ucfirst($order['status']); ?></p>
        <p><strong>Date:</strong> <?php echo date('F j, Y g:i A', strtotime($order['created_at'])); ?></p>
    </div>
    
    <h2>Order Items</h2>
    <table class="items-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                <td>$<?php echo number_format($item['price'], 2); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="total">
        Total: $<?php echo number_format($order['total_amount'], 2); ?>
    </div>
</body>
</html>