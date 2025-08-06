<?php 
include '../includes/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    die('Access denied. Admin only.');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $image = trim($_POST['image']);
    
    if (empty($name) || empty($description) || $price <= 0) {
        $error = 'Please fill in all fields with valid data';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image, stock) VALUES (?, ?, ?, ?, ?)");
            
            if ($stmt->execute([$name, $description, $price, $image, $stock])) {
                $success = 'Product added successfully!';
                $_POST = [];
            } else {
                $error = 'Failed to add product';
            }
        } catch(PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Admin</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div style="padding: 2rem;">
        <h1>Add New Product</h1>
        <a href="../index.php" style="margin-bottom: 2rem; display: inline-block;">← Back to Store</a>
        <a href="view_orders.php" style="margin-bottom: 2rem; display: inline-block; margin-left: 1rem;">View Orders</a>
        
        <?php if ($error): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="message success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <div class="form-container">
            <form method="POST">
                <div class="form-group">
                    <label for="name">Product Name:</label>
                    <input type="text" id="name" name="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="price">Price ($):</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" required value="<?php echo isset($_POST['price']) ? $_POST['price'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="stock">Stock Quantity:</label>
                    <input type="number" id="stock" name="stock" min="0" required value="<?php echo isset($_POST['stock']) ? $_POST['stock'] : '0'; ?>">
                </div>
                
                <div class="form-group">
                    <label for="image">Image URL (optional):</label>
                    <input type="url" id="image" name="image" value="<?php echo isset($_POST['image']) ? htmlspecialchars($_POST['image']) : ''; ?>">
                </div>
                
                <button type="submit" class="btn">Add Product</button>
            </form>
        </div>
    </div>
</body>
</html>
```

## admin/view_orders.php
```php
<?php 
include '../includes/db.php';

// Simple admin check (you should implement proper admin authentication)
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    die('Access denied. Admin only.');
}

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);
}

try {
    $stmt = $pdo->query("
        SELECT o.*, u.username, u.email 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC
    ");
    $orders = $stmt->fetchAll();
} catch(PDOException $e) {
    $orders = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders - Admin</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
        }
        .orders-table th,
        .orders-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .orders-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .status-pending { color: #f39c12; }
        .status-processing { color: #3498db; }
        .status-completed { color: #27ae60; }
        .status-cancelled { color: #e74c3c; }
    </style>
</head>
<body>
    <div style="padding: 2rem;">
        <h1>Orders Management</h1>
        <a href="../index.php" style="margin-bottom: 2rem; display: inline-block;">← Back to Store</a>
        <a href="add_product.php" style="margin-bottom: 2rem; display: inline-block; margin-left: 1rem;">Add Product</a>
        
        <?php if (empty($orders)): ?>
        <p>No orders found.</p>
        <?php else: ?>
        
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?php echo $order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['username']); ?></td>
                    <td><?php echo htmlspecialchars($order['email']); ?></td>
                    <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                    <td class="status-<?php echo $order['status']; ?>">
                        <?php echo ucfirst($order['status']); ?>
                    </td>
                    <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <select name="status" onchange="this.form.submit()">
                                <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                        <button onclick="viewOrderDetails(<?php echo $order['id']; ?>)" class="btn" style="margin-left: 10px; padding: 5px 10px; font-size: 0.8rem;">Details</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php endif; ?>
    </div>
    
    <script>
    function viewOrderDetails(orderId) {
        // Simple order details display
        fetch(`../order_details.php?id=${orderId}`)
            .then(response => response.text())
            .then(data => {
                const popup = window.open('', '_blank', 'width=600,height=400');
                popup.document.write(data);
            })
            .catch(error => {
                alert('Error loading order details');
            });
    }
    </script>
</body>
</html>