<?php
session_start();
require_once(__DIR__ . '/../../db/config.php');

$login_success = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE name = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $_SESSION['user'] = $username;
        $login_success = true;
    } else {
        $error = "Invalid login credentials. Please try again.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login | Car Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-box {
            background: #f9f9f9;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-control:focus {
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.6);
            border-color: #3498db;
        }

        .btn-primary {
            background-color: #3498db;
            border: none;
            transition: all 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            box-shadow: 0 0 12px rgba(52, 152, 219, 0.6);
            transform: scale(1.03);
        }

        .text-center h2 {
            font-weight: bold;
            color: #333;
        }

        .error-msg {
            color: red;
            font-size: 0.9rem;
            margin-bottom: 10px;
            text-align: center;
        }

        .success-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #e0ffe0;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 128, 0, 0.2);
            animation: fadeIn 1s ease-in-out;
        }

        .success-container .checkmark {
            font-size: 4rem;
            color: #2ecc71;
            animation: pop 0.5s ease;
        }

        .success-container h4 {
            margin-top: 15px;
            font-weight: 600;
            color: #2ecc71;
            animation: slideUp 0.6s ease;
        }

        @keyframes pop {
            0% { transform: scale(0); opacity: 0; }
            100% { transform: scale(1.2); opacity: 1; }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<?php if ($login_success): ?>
    <div class="success-container">
        <div class="checkmark">&#10004;</div>
        <h4>Login Successful!</h4>
    </div>
    <script>
        setTimeout(() => {
            window.location.href = "../../admin/dashboard.php";
        }, 2000);
    </script>
<?php else: ?>
    <div class="login-box">
        <h2 class="text-center mb-4">User Login</h2>

        <?php if ($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
<?php endif; ?>

</body>
</html>
