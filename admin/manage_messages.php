<?php
require_once("../db/config.php");
include("includes/header.php");

// Fetch messages from database
$messages = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
?>

<style>
    body {
        background: linear-gradient(to right, #f5f7fa, #c3cfe2);
        font-family: 'Poppins', sans-serif;
    }

    .messages-container {
        max-width: 1200px;
        margin: 50px auto;
        padding: 30px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        animation: fadeIn 0.8s ease;
    }

    .message-card {
        background: #fdfdfd;
        margin-bottom: 20px;
        padding: 20px 25px;
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        transform-style: preserve-3d;
    }

    .message-card:hover {
        transform: rotateX(4deg) rotateY(-4deg) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .message-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .message-email {
        color: #007bff;
    }

    .message-date {
        font-size: 0.85rem;
        color: #888;
    }

    .message-body {
        font-size: 1rem;
        line-height: 1.6;
        color: #333;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="messages-container">
    <h2 class="text-center mb-4">📩 User Messages</h2>

    <?php if ($messages->num_rows > 0): ?>
        <?php while ($msg = $messages->fetch_assoc()): ?>
            <div class="message-card">
                <div class="message-header">
                    <span class="message-email"><?= htmlspecialchars($msg['email']) ?></span>
                    <span class="message-date"><?= date("d M Y, h:i A", strtotime($msg['created_at'])) ?></span>
                </div>
                <div class="message-body">
                    <?= nl2br(htmlspecialchars($msg['message'])) ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-info text-center">No messages found.</div>
    <?php endif; ?>
</div>

<?php include("includes/footer.php"); ?>
