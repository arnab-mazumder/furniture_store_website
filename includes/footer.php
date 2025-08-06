    </main>
    <footer>
    <div class="footer-container">
        <div class="footer-section">
            <h3>Furniture Store</h3>
            <p>Quality furniture for your home and office.</p>
        </div>
        <div class="footer-section">
        <h3>Quick Links</h3>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="profile.php">Profile</a></li>
            <?php endif; ?>
        </ul>
        <hr style="border:0;border-top:1px solid #d4dee9; margin:10px 0 12px 0;">
        <ul class="footer-extra-links">
            <li><a href="about.php">About Us</a></li>
            <li><a href="terms.php">Terms &amp; Conditions</a></li>
            <li><a href="feedback.php">Feedback</a></li>
        </ul>
    </div>
        <div class="footer-section">
            <h3>Contact</h3>
            <p>Email: <a href="mailto:info@furniturestore.com" class="footer-contact-link">info@furniturestore.com</a></p>
            <p>Phone: <a href="tel:9902020101" class="footer-contact-link">+91 99020 20101</a></p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 Furniture Store. All rights reserved.</p>
    </div>
</footer>

    <div id="custom-remove-modal">
      <div class="modal-backdrop"></div>
      <div class="modal-card">
        <div class="modal-msg">
          <i class="fa fa-exclamation-circle" style="font-size:2em; color:#dc7e18; margin-bottom:.5em;"></i>
          <div class="modal-text">Are you sure you want to remove this item from your cart?</div>
        </div>
        <div class="modal-actions">
          <button class="btn btn-secondary" id="remove-cancel-btn" type="button">Cancel</button>
          <button class="btn btn-danger" id="remove-ok-btn" type="button">OK</button>
        </div>
      </div>
    </div>

    <div id="page-loader">
      <div class="spinner">
        <svg viewBox="0 0 50 50">
          <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
        </svg>
      </div>
    </div>

    <script src="js/scripts.js"></script>
    </body>
</html>
