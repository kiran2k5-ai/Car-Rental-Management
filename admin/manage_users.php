<?php
session_start();
require_once("../db/config.php");
include("includes/header.php");

// Handle user update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $id = $_POST['user_id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $email, $id);
    $stmt->execute();
    $stmt->close();
}

// Handle delete
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $conn->query("DELETE FROM users WHERE id = $delete_id");
}
?>

<style>
    body {
        background: #f5f6fa;
    }

    .container {
        max-width: 1000px;
        padding: 30px;
        margin: 50px auto;
    }

    .table-container {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        animation: fadeIn 0.8s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
    }

    table th {
        background-color: #343a40;
        color: white;
    }

    table td, table th {
        vertical-align: middle !important;
    }

    .form-inline input {
        width: 100%;
        padding: 4px 6px;
    }

    .btn-sm {
        font-size: 0.8rem;
    }

    .search-bar {
        margin-bottom: 20px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
</style>

<div class="container">
    <h2>👤 Manage Users</h2>

    <form method="GET" class="search-bar">
        <input type="text" name="search" class="form-control w-25" placeholder="Search by name or email" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button type="submit" class="btn btn-outline-primary">🔍 Search</button>
    </form>

    <div class="table-container">
        <table class="table table-bordered text-center align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $search = $_GET['search'] ?? '';
                $search = $conn->real_escape_string($search);

                $query = !empty($search)
                    ? "SELECT * FROM users WHERE name LIKE '%$search%' OR email LIKE '%$search%' ORDER BY id DESC"
                    : "SELECT * FROM users ORDER BY id DESC";

                $result = $conn->query($query);

                if ($result && $result->num_rows > 0):
                    while ($user = $result->fetch_assoc()):
                ?>
                <tr>
                    <form method="POST" class="form-inline">
                        <td><?= $user['id']; ?></td>
                        <td><input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>"></td>
                        <td><input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>"></td>
                        <td><?= htmlspecialchars($user['role']); ?></td>
                        <td>
                            <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                            <button type="submit" name="edit_user" class="btn btn-sm btn-primary">💾 Save</button>
                            <a href="?delete=<?= $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?');">🗑️ Delete</a>
                        </td>
                    </form>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="5">No users found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("includes/footer.php"); ?>
