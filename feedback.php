<div class="site-bg-overlay"></div>
<?php
session_start();
include 'includes/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $err = '';
    $success = '';
    if ($name === "" || $email === "" || $message === "") {
        $err = "All fields are required.";
    } else {
        $success = "Thank you for sharing your feedback!";
    }
}
?>
<div class="container" style="max-width:520px;">
    <h1>Feedback</h1>
    <?php if (!empty($err)): ?>
        <div class="message error"><?php echo $err; ?></div>
    <?php elseif (!empty($success)): ?>
        <div class="message success"><?php echo $success; ?></div>
    <?php endif; ?>
    <form method="post" class="form-container">
        <div class="form-group">
            <label for="name">Name *</label>
            <input required type="text" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="email">Email *</label>
            <input required type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="message">Message *</label>
            <textarea required name="message" id="message" rows="5"><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
        </div>
        <button class="btn" type="submit">Send Feedback</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
