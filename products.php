<div class="site-bg-overlay"></div>
<?php
session_start();
require_once 'includes/db.php';
include 'includes/header.php';
?>

<div class="container">
    <h1>Our Products</h1>
    <?php
    try {
        $stmt = $pdo->query("SELECT * FROM products ORDER BY name");
        $products = $stmt->fetchAll();
    } catch (PDOException $e) {
        $products = [];
        echo "<p class='message error'>Error loading products: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    ?>

    <div class="products-grid">
<?php foreach ($products as $product): ?>
    <?php
        $imageRaw = $product['image'];
        if (!preg_match('/^(http|https):\/\//', $imageRaw)) {
            $imageSrc = 'images/products/' . ltrim($imageRaw, '/');
        } else {
            $imageSrc = $imageRaw;
        }
    ?>
    <div class="product-card">
        <img 
            src="<?php echo htmlspecialchars($imageSrc); ?>" 
            alt="<?php echo htmlspecialchars($product['name']); ?>"
            onerror="this.src='images/products/placeholder.png';"
        >
        <div class="product-info">
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <div class="product-price">&#8377;<?php echo number_format($product['price'], 2); ?></div>
            <p>Stock: <?php echo (int)$product['stock']; ?> available</p>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($product['stock'] > 0): ?>
                    <button class="btn"
                        onclick='addToCart(
                            <?php echo (int)$product["id"]; ?>,
                            <?php echo json_encode($product["name"]); ?>,
                            <?php echo (float)$product["price"]; ?>,
                            <?php echo json_encode($imageSrc); ?>
                        )'>
                        Add to Cart
                    </button>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled>Out of Stock</button>
                <?php endif; ?>
            <?php else: ?>
                <a href="login.php" class="btn">Login to Purchase</a>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
    </div>
</div>

<script>
function showMessage(message, type) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}`;
    messageDiv.textContent = message;
    const container = document.querySelector('.container') || document.querySelector('main') || document.body;
    container.insertBefore(messageDiv, container.firstChild);
    setTimeout(() => { messageDiv.remove(); }, 3000);
}

function addToCart(productId, productName, price, image) {
    fetch('cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=add&product_id=${productId}&product_name=${encodeURIComponent(productName)}&price=${price}&image=${encodeURIComponent(image)}`
    })
    .then(response => {
        const contentType = response.headers.get('content-type') || '';
        if (!contentType.includes('application/json')) {
            return response.text().then(text => { throw new Error(text); });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            let cartCountElements = document.querySelectorAll('#cart-count');
            cartCountElements.forEach(el => el.textContent = data.cart_count);
            showMessage('Product added to cart!', 'success');
        } else if (data.message && data.message.toLowerCase().includes('log in')) {
            window.location.href = 'login.php';
        } else {
            showMessage(data.message || 'Error adding to cart', 'error');
        }
    })
    .catch(error => {
        showMessage('Error adding to cart: ' + error.message, 'error');
        console.error('Add to cart error:', error);
    });
}
</script>

<?php include 'includes/footer.php'; ?>
