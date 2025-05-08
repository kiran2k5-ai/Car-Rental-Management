<?php
require_once("db/config.php");

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $user_message = trim($_POST['message']);

    if (!empty($email) && !empty($user_message)) {
        $stmt = $conn->prepare("INSERT INTO messages (email, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $user_message);
        $stmt->execute();
        $stmt->close();
        $message = "✅ Message sent successfully!";
    } else {
        $message = "❌ All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e3f2fd, #ffffff);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
        }

        .contact-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
            animation: fadeIn 0.8s ease;
            transition: transform 0.4s ease;
        }

        .contact-card:hover {
            transform: scale(1.02) rotateX(4deg) rotateY(4deg);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .contact-card h2 {
            text-align: center;
            font-weight: 700;
            color: #333;
            margin-bottom: 25px;
        }

        .form-control, textarea {
            border-radius: 8px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #0d6efd, #0056b3);
            border: none;
            padding: 12px;
            color: white;
            font-weight: bold;
            border-radius: 10px;
            transition: 0.3s ease;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #0056b3, #003a80);
        }

        .message-box {
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="contact-card">
    <h2>📩 Contact Us</h2>

    <?php if ($message): ?>
        <div class="message-box text-<?= str_starts_with($message, '✅') ? 'success' : 'danger' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Your Message</label>
            <textarea name="message" rows="4" class="form-control" placeholder="Write your message here..." required></textarea>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-submit">Send Message</button>
        </div>
    </form>
</div>

</body>
</html>
