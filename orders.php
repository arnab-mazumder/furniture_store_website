<div class="site-bg-overlay"></div>
<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT id, created_at, total_amount, status FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h1>Your Orders</h1>
    <?php if (empty($orders)): ?>
        <div class="message">You have no orders yet. <a href="products.php">Start Shopping</a></div>
    <?php else: ?>
        <div class="orders-table-wrapper">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
<?php foreach ($orders as $row): ?>
    <tr>
        <td>#<?php echo $row['id']; ?></td>
        <td><?php echo date("Y-m-d H:i", strtotime($row['created_at'])); ?></td>
        <td>
            <span class="order-status status-<?php echo strtolower($row['status']); ?>">
                <?php echo htmlspecialchars($row['status']); ?>
            </span>
        </td>
        <td>$<?php echo number_format($row['total_amount'], 2); ?></td>
        <td>
            <a class="order-link" href="order_details.php?order_id=<?php echo $row['id']; ?>">View</a>
            |
            <a class="order-link" href="download_bill.php?order_id=<?php echo $row['id']; ?>" target="_blank">
                <i class="fa fa-download"></i> Bill
            </a>
        </td>
    </tr>
<?php endforeach; ?>
</tbody>

            </table>
        </div>
    <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>
