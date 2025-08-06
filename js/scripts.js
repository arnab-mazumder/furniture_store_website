// ------------------------
// Toast Message (shows floating message for 3s)
// ------------------------
function showMessage(message, type) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}`;
    messageDiv.textContent = message;
    const container = document.querySelector('.container') || document.querySelector('main') || document.body;
    container.insertBefore(messageDiv, container.firstChild);
    setTimeout(() => { messageDiv.remove(); }, 3000);
}

// ------------------------
// Add to Cart
// ------------------------
function addToCart(productId, productName, price, image) {
    fetch('cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=add&product_id=${productId}&product_name=${encodeURIComponent(productName)}&price=${price}&image=${encodeURIComponent(image)}`
    })
    .then(response => {
        const contentType = response.headers.get('content-type') || '';
        if (!contentType.includes('application/json')) {
            // Probably received HTML error, login page, etc.
            return response.text().then(text => {
                const temp = document.createElement('div');
                temp.innerHTML = text;
                const msg = temp.textContent || text;
                throw new Error(msg.length > 170 ? msg.slice(0, 170) + '...' : msg);
            });
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

// ------------------------
// Remove from Cart with Modal
// ------------------------
function removeFromCart(productId) {
    showRemoveModal(function() {
        fetch('cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=remove&product_id=${productId}`
        })
        .then(response => {
            const contentType = response.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                return response.text().then(text => {
                    const temp = document.createElement('div');
                    temp.innerHTML = text;
                    const msg = temp.textContent || text;
                    throw new Error(msg.length > 170 ? msg.slice(0, 170) + '...' : msg);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showMessage('Item removed from cart!', 'success');
                setTimeout(() => location.reload(), 700);
            } else {
                showMessage(data.message || 'Error removing item', 'error');
            }
        })
        .catch(error => {
            showMessage('Error removing item: ' + error.message, 'error');
            console.error('Remove item error:', error);
        });
    });
}

function showRemoveModal(onOK) {
    const modal = document.getElementById('custom-remove-modal');
    if (!modal) return;
    modal.classList.add('active');
    const okBtn = document.getElementById('remove-ok-btn');
    const cancelBtn = document.getElementById('remove-cancel-btn');
    if (!okBtn || !cancelBtn) return;
    okBtn.onclick = cancelBtn.onclick = null;
    okBtn.onclick = function() {
        modal.classList.remove('active');
        if (typeof onOK === 'function') onOK();
    };
    cancelBtn.onclick = function() {
        modal.classList.remove('active');
    };
    window.onkeydown = function(ev) {
        if (ev.key === "Escape" && modal.classList.contains('active')) {
            modal.classList.remove('active');
            window.onkeydown = null;
        }
    };
}

// ------------------------
// Update Cart Quantity
// ------------------------
function updateQuantity(productId, quantity) {
    if (quantity < 1) {
        removeFromCart(productId);
        return;
    }
    fetch('cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=update&product_id=${productId}&quantity=${quantity}`
    })
    .then(response => {
        const contentType = response.headers.get('content-type') || '';
        if (!contentType.includes('application/json')) {
            return response.text().then(text => {
                const temp = document.createElement('div');
                temp.innerHTML = text;
                const msg = temp.textContent || text;
                throw new Error(msg.length > 170 ? msg.slice(0, 170) + '...' : msg);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            showMessage(data.message || 'Error updating cart', 'error');
        }
    })
    .catch(error => {
        showMessage('Error updating cart: ' + error.message, 'error');
        console.error('Update quantity error:', error);
    });
}

// ------------------------
// Form Validation
// ------------------------
document.addEventListener('DOMContentLoaded', function() {
    // Registration form validation
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            if (password !== confirmPassword) {
                e.preventDefault();
                showMessage('Passwords do not match!', 'error');
            }
        });
    }
    // Checkout form validation
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            const required = checkoutForm.querySelectorAll('[required]');
            let valid = true;
            required.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.style.borderColor = '#e74c3c';
                } else {
                    field.style.borderColor = '#ddd';
                }
            });
            if (!valid) {
                e.preventDefault();
                showMessage('Please fill in all required fields', 'error');
            }
        });
    }
});

// ------------------------
// Page Loader on Navigation
// ------------------------
document.addEventListener('DOMContentLoaded', function() {
    const loader = document.getElementById('page-loader');
    document.body.setAttribute('data-ready','true');
    document.querySelectorAll('a[href]').forEach(link => {
        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') ||
            link.target === "_blank" ||
            link.hasAttribute('download')) return;
        link.addEventListener('click', function(e) {
            if (
                this.hostname === location.hostname &&
                this.href !== location.href &&
                !this.hasAttribute('data-no-loader')
            ) {
                e.preventDefault();
                if (loader) loader.classList.add('active');
                setTimeout(() => { location.href = this.href; }, 400);
            }
        });
    });
});

// ------------------------
// Profile Dropdown Menu
// ------------------------
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('profileMenuBtn');
    var dropdown = document.getElementById('profileDropdown');
    if (!btn || !dropdown) return;
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdown.classList.toggle('show');
    });
    document.addEventListener('click', function(e) {
        if (!dropdown.contains(e.target) && !btn.contains(e.target))
            dropdown.classList.remove('show');
    });
});
