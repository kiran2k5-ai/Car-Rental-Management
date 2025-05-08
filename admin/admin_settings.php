<?php
session_start();
require_once("../db/config.php");

// Ensure admin is logged in
// if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
//     header("Location: ../login.php");
//     exit;
// }

$admin = $_SESSION['user'];
$message = '';

// Update profile logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);

    $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $phone, $admin['id']);

    if ($stmt->execute()) {
        $message = "✅ Profile updated successfully!";
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['phone'] = $phone;
    } else {
        $message = "❌ Failed to update profile.";
    }

    $stmt->close();
}

// Change password logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $new_password, $admin['id']);

    if ($stmt->execute()) {
        $message = "🔒 Password updated successfully!";
    } else {
        $message = "❌ Failed to change password.";
    }

    $stmt->close();
}

include("includes/header.php");
?>

<style>
    body {
        background: #f0f4f8;
        font-family: 'Poppins', sans-serif;
    }

    .settings-container {
        max-width: 900px;
        margin: 40px auto;
        background: white;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        font-weight: 600;
        color: #333;
    }

    label {
        font-weight: 500;
        color: #555;
    }

    .form-control {
        border-radius: 8px;
    }

    .btn-custom {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        font-weight: 500;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        transition: all 0.3s ease;
    }

    .btn-custom:hover {
        background: linear-gradient(135deg, #0056b3, #004494);
        transform: scale(1.05);
    }

    .section-title {
        font-size: 20px;
        margin-top: 30px;
        color: #2c3e50;
        border-bottom: 2px solid #e3e3e3;
        padding-bottom: 10px;
    }

    .alert {
        font-size: 16px;
    }
</style>

<div class="settings-container">
    <h2>⚙️ Admin Settings</h2>

    <?php if ($message): ?>
        <div class="alert alert-info text-center"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="section-title">👤 Profile Information</div>

        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($admin['name']); ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email (readonly):</label>
            <input type="email" value="<?= htmlspecialchars($admin['email']); ?>" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label>Phone:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($admin['phone'] ?? ''); ?>" class="form-control">
        </div>

        <div class="mb-4">
            <button type="submit" name="update_profile" class="btn btn-custom">💾 Update Profile</button>
        </div>
    </form>

    <form method="POST">
        <div class="section-title">🔐 Security</div>

        <div class="mb-3">
            <label>New Password:</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>

        <div class="mb-4">
            <button type="submit" name="change_password" class="btn btn-custom">🔁 Change Password</button>
        </div>
    </form>

    <div class="section-title">⚙️ System Preferences (Coming Soon)</div>
    <p style="color: #666;">Customize admin dashboard themes, enable/disable features, and more.</p>
</div>

<?php include("includes/footer.php"); ?>
