<div class="site-bg-overlay"></div>
<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

// 1. Fetch user profile info
$stmt = $pdo->prepare("SELECT full_name, phone, address, city, state, zip FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

// 2. Check if profile is incomplete
$missing = [];
foreach (['full_name','phone','address','city','state','zip'] as $field) {
    if (empty($profile[$field]) || trim($profile[$field]) === "") $missing[] = $field;
}
if (!empty($missing)) {
    $_SESSION['flash_error'] = "Please complete your profile before checking out.";
    header("Location: profile.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo->beginTransaction();
        // Calculate order total
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        // Create order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (?, ?, 'pending', NOW())");
        $stmt->execute([$_SESSION['user_id'], $total]);
        $order_id = $pdo->lastInsertId();
        // Add order items
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($_SESSION['cart'] as $product_id => $item) {
            $stmt->execute([$order_id, $product_id, $item['quantity'], $item['price']]);
            // Update stock
            $update_stock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            $update_stock->execute([$item['quantity'], $product_id]);
        }
        $pdo->commit();
        unset($_SESSION['cart']);
        $success = "Order placed successfully! Order ID: #$order_id";
    } catch(PDOException $e) {
        $pdo->rollBack();
        $error = 'Order failed: ' . $e->getMessage();
    }
}

// Calculate total for display
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<style>
.checkout-bg {
    position: fixed;
    z-index: 0;
    left: 0; top: 0; right: 0; bottom: 0;
    background:
        linear-gradient(120deg, #183153ee 30%, #1563a5bc 100%),
        url('https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=1200&q=80');
    background-size: cover;
    background-position: center;
    opacity: 0.14;
    pointer-events: none;
}
.checkout-content {
    position: relative;
    z-index: 1;
}
@media (max-width: 900px) {
    .checkout-flex { grid-template-columns: 1fr !important; }
}
</style>
<div class="checkout-bg"></div>

<div class="container checkout-content">
    <h1 style="letter-spacing:.03em;color:#24416d;text-shadow:0 6px 28px #18315318;margin-bottom: .8em;">Checkout</h1>
    <?php if ($error): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="message success"><?php echo htmlspecialchars($success); ?></div>
        <a href="products.php" class="btn" style="margin-top:2em;">Continue Shopping</a>
    <?php elseif (!empty($_SESSION['cart'])): ?>
    <div
        class="checkout-flex"
        style="
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2.5rem;
            margin-top: 2rem;
            max-width: 900px;
        "
    >
        <!-- Left: Order Summary Card -->
        <div style="
            background: rgba(255,255,255,0.93);
            border-radius: 16px;
            box-shadow: 0 7px 24px 0 #1662a91b;
            padding: 2.2rem 2rem 2rem 2rem;
            border: 1.9px solid #e4ecf5;
            min-width: 0;
        ">
            <h2 style="color: #1467aa; font-weight:700; font-size:1.35rem; margin-bottom:1.1rem;">Order Summary</h2>
            <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                <div style="
                    display: flex; justify-content: space-between; align-items: center;
                    padding: 0.56rem 0; border-bottom: 1px solid #e7eef7;
                    font-size: 1.04rem;">
                    <span><?php echo htmlspecialchars($item['name']); ?> <span style="color:#469fb6;font-size:.97em;">× <?php echo $item['quantity']; ?></span></span>
                    <span style="font-weight:600;">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                </div>
            <?php endforeach; ?>
            <div style="
                display: flex; justify-content: space-between; align-items: center;
                padding: 1.2rem 0 0 0;
                font-weight: bold; font-size: 1.15rem;
                border-top: 2px solid #e2eefd;
                margin-top: 1.2em;">
                <span style="color: #366fff;">Total:</span>
                <span style="color: #d03b2f;">$<?php echo number_format($total, 2); ?></span>
            </div>
        </div>

        <!-- Right: Shipping (Profile) Information Card -->
        <div style="
            background: rgba(252,255,255,0.93);
            border-radius: 16px;
            box-shadow: 0 7px 24px 0 #1662a91b;
            padding: 2.2rem 2rem 2rem 2rem;
            border: 1.9px solid #e4ecf5;
        ">
            <h2 style="color:#1467aa; font-weight:700; font-size:1.35rem; margin-bottom:1.1rem;">Shipping Information</h2>
            <div class="form-group"><label>Full Name:</label>
                <div><?php echo htmlspecialchars($profile['full_name']); ?></div>
            </div>
            <div class="form-group"><label>Phone:</label>
                <div><?php echo htmlspecialchars($profile['phone']); ?></div>
            </div>
            <div class="form-group"><label>Address:</label>
                <div><?php echo htmlspecialchars($profile['address']); ?></div>
            </div>
            <div class="form-group"><label>City:</label>
                <div><?php echo htmlspecialchars($profile['city']); ?></div>
            </div>
            <div class="form-group"><label>State:</label>
                <div><?php echo htmlspecialchars($profile['state']); ?></div>
            </div>
            <div class="form-group"><label>ZIP Code:</label>
                <div><?php echo htmlspecialchars($profile['zip']); ?></div>
            </div>
            <form method="POST" id="checkoutForm">
                <div style="display:flex; flex-wrap:wrap; gap:12px; margin-top:10px;">
                    <button type="submit" class="btn">Place Order</button>
                    <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
                    <a href="profile.php" class="btn btn-secondary">Edit Profile</a>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
