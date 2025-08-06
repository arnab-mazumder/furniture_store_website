<div class="site-bg-overlay"></div>
<?php include 'includes/header.php'; ?>

<section class="hero">
    <div class="hero-content">
        <h1>Quality Furniture for Your Home</h1>
        <p>Discover our collection of modern and classic furniture pieces</p>
        <a href="products.php" class="btn">Shop Now</a>
    </div>
</section>

<div class="container">
    <h2>Featured Products</h2>
    
    <?php
    try {
        $stmt = $pdo->query("SELECT * FROM products ORDER BY RAND() LIMIT 6");
        $products = $stmt->fetchAll();
    } catch(PDOException $e) {
        $products = [];
    }
    ?>
    
    <div class="products-grid">
    <?php foreach ($products as $product): ?>
        <div class="product-card">
            <?php
            $imageSrc = htmlspecialchars($product['image']);
            if (!preg_match('/^(http|https):\/\//', $imageSrc)) {
                $imageSrc = 'images/products/' . $imageSrc;
            }
            ?>
            <img src="<?php echo $imageSrc; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <div class="product-info">
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <div class="product-price">&#8377;<?php echo number_format($product['price'], 2); ?></div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="btn" onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['price']; ?>)">
                        Add to Cart
                    </button>
                <?php else: ?>
                    <a href="login.php" class="btn">Login to Purchase</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
