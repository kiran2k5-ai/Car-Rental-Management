<?php
require_once("db/config.php");

 include("includes/header.php"); 
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password']; // Plain text (for demo only)
    $phone = trim($_POST['phone']);
    $role = 'user';
    $created_at = date('Y-m-d H:i:s');

    if (empty($name) || empty($email) || empty($password) || empty($phone)) {
        $message = "❌ All fields are required.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "❌ Email is already registered.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role, created_at) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $name, $email, $password, $phone, $role, $created_at);

            if ($stmt->execute()) {
                $message = "✅ Registration successful. You can now <a href='login.php'>login</a>.";
            } else {
                $message = "❌ Registration failed: " . $stmt->error;
            }
        }

        $stmt->close();
    }
}
?>


<!-- Load Google Font and Font Awesome -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #dff1ff, #f7faff);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px;
    }

    .register-card {
        background: #fff;
        max-width: 480px;
        width: 100%;
        border-radius: 15px;
        padding: 40px 30px;
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.1);
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(40px);}
        to {opacity: 1; transform: translateY(0);}
    }

    .register-card h2 {
        text-align: center;
        font-weight: 700;
        margin-bottom: 25px;
        color: #333;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 6px;
    }

    .input-group-text {
        background: #f0f4f8;
        border: none;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #0d6efd;
    }

    .btn-primary {
        font-weight: 600;
        padding: 10px 0;
    }

    .alert-info {
        font-size: 0.95rem;
        margin-top: 10px;
    }
</style>

<div class="register-card">
    <h2>👤 Create Account</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="mb-3">
            <label class="form-label">Name</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" name="name" class="form-control" placeholder="Full name" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="text" name="password" class="form-control" placeholder="Choose a password" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                <input type="text" name="phone" class="form-control" placeholder="Your phone number" required>
            </div>
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Register</button>
        </div>
    </form>
</div>

<?php include("includes/footer.php"); ?>
