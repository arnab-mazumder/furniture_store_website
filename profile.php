<div class="site-bg-overlay"></div>
<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $zip = trim($_POST['zip'] ?? '');

    $err = null;
    if ($full_name === '' || $phone === '' || $address === '' || $city === '' || $state === '' || $zip === '') {
        $err = "Please fill in all fields.";
    } else {
        $stmt = $pdo->prepare("UPDATE users SET full_name=?, phone=?, address=?, city=?, state=?, zip=? WHERE id=?");
        $stmt->execute([$full_name, $phone, $address, $city, $state, $zip, $_SESSION['user_id']]);
        $success = true;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
<?php if (!empty($_SESSION['flash_error'])): ?>
  <div class="message error"><?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
<?php endif; ?>

<div class="container" style="max-width:490px">
  <h1>Your Profile</h1>
  <?php if (!empty($err)): ?>
    <div class="message error"><?php echo $err ?></div>
  <?php elseif (!empty($success)): ?>
    <div class="message success">Profile updated successfully!</div>
  <?php endif; ?>

  <form method="post" class="form-container" autocomplete="off">
    <div class="form-group">
      <label for="full_name">Full Name</label>
      <input required type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>">
    </div>
    <div class="form-group">
      <label for="phone">Phone</label>
      <input required type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
    </div>
    <div class="form-group">
      <label for="address">Address</label>
      <input required type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">
    </div>
    <div class="form-group">
      <label for="city">City</label>
      <input required type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>">
    </div>
    <div class="form-group">
      <label for="state">State</label>
      <input required type="text" id="state" name="state" value="<?php echo htmlspecialchars($user['state'] ?? ''); ?>">
    </div>
    <div class="form-group">
      <label for="zip">Zip/Postal Code</label>
      <input required type="text" id="zip" name="zip" value="<?php echo htmlspecialchars($user['zip'] ?? ''); ?>">
    </div>
    <button type="submit" class="btn">Save Profile</button>
  </form>
</div>

<?php include 'includes/footer.php'; ?>
