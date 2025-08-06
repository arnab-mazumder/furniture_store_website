<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Please log in first']);
        exit;
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    switch ($_POST['action']) {
        case 'add':
            if (!isset($_POST['product_id'], $_POST['product_name'], $_POST['price'], $_POST['image'])) {
                echo json_encode(['success' => false, 'message' => 'Incomplete request']);
                exit;
            }
            $product_id = (int)$_POST['product_id'];
            $product_name = $_POST['product_name'];
            $price = (float)$_POST['price'];
            $image = $_POST['image'];
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity']++;
            } else {
                $_SESSION['cart'][$product_id] = [
                    'name' => $product_name,
                    'price' => $price,
                    'image' => $image,
                    'quantity' => 1
                ];
            }
            $cart_qty = array_sum(array_column($_SESSION['cart'], 'quantity'));
            echo json_encode(['success' => true, 'cart_count' => $cart_qty]);
            exit;

        case 'update':
            if (!isset($_POST['product_id'], $_POST['quantity'])) {
                echo json_encode(['success' => false, 'message' => 'Incomplete request']);
                exit;
            }
            $product_id = (int)$_POST['product_id'];
            $quantity = (int)$_POST['quantity'];
            if ($quantity > 0 && isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
            }
            exit;

        case 'remove':
            if (!isset($_POST['product_id'])) {
                echo json_encode(['success' => false, 'message' => 'No product specified']);
                exit;
            }
            $product_id = (int)$_POST['product_id'];
            unset($_SESSION['cart'][$product_id]);
            echo json_encode(['success' => true]);
            exit;

        default:
            echo json_encode(['success' => false, 'message' => 'Unknown action']);
            exit;
    }
    exit;
}
?>

<div class="site-bg-overlay"></div>
<?php
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<div class="container">
    <h1>Shopping Cart</h1>
    <?php if (empty($_SESSION['cart'])): ?>
        <div class="message">Your cart is empty. <a href="products.php">Continue shopping</a></div>
    <?php else: ?>
        <div class="cart-items">
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $product_id => $item):
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
                $img = (!empty($item['image'])) ? htmlspecialchars($item['image']) : 'images/products/placeholder.png';
            ?>
            <div class="cart-item">
                <img
                    src="<?php echo $img; ?>"
                    alt="<?php echo htmlspecialchars($item['name']); ?>"
                    onerror="this.src='images/products/placeholder.png';"
                    style="width:80px; height:80px; object-fit:cover; border-radius:6px; border:1.5px solid #e4ecf3;"
                >
                <div class="cart-item-info">
                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                    <p>&#8377;<?php echo number_format($item['price'], 2); ?> each</p>
                </div>
                <div class="cart-item-controls">
                    <input type="number"
                        value="<?php echo $item['quantity']; ?>"
                        min="1"
                        onchange="updateQuantity(<?php echo (int)$product_id; ?>, this.value)">
                    <button class="btn btn-secondary" onclick="removeFromCart(<?php echo (int)$product_id; ?>)">Remove</button>
                </div>
                <div class="cart-item-total">
                    &#8377;<?php echo number_format($subtotal, 2); ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="cart-total">
            <h3>Total: &#8377;<?php echo number_format($total, 2); ?></h3>
            <a href="checkout.php" class="btn">Proceed to Checkout</a>
            <a href="products.php" class="btn btn-secondary">Continue Shopping</a>
        </div>
    <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>
