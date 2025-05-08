<?php
session_start();
require_once("../db/config.php");

// if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
//     exit("Unauthorized access.");
// }

$message = '';
$backupDir = realpath(__DIR__ . '/../backups');
if (!$backupDir) {
    mkdir(__DIR__ . '/../backups', 0777, true);
    $backupDir = realpath(__DIR__ . '/../backups');
}

// Full paths to mysqldump and mysql
$mysqldumpPath = "C:\\xampp\\mysql\\bin\\mysqldump.exe";
$mysqlPath     = "C:\\xampp\\mysql\\bin\\mysql.exe";
$dbUser        = "root";
$dbPass        = "";  // If there's no password
$dbName        = "car_rental";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['backup'])) {
        $backupFile = $backupDir . "/backup_" . date("Y-m-d_H-i-s") . ".sql";
        $cmd = "\"$mysqldumpPath\" --user=$dbUser $dbName > \"$backupFile\"";
        exec("cmd /c $cmd", $output, $result);
        $message = $result === 0 ? "✅ Backup completed: " . basename($backupFile) : "❌ Backup failed. Check server permissions.";
    }

    if (isset($_POST['restore']) && isset($_FILES['sql_file'])) {
        $fileTmp = $_FILES['sql_file']['tmp_name'];
        if (is_uploaded_file($fileTmp)) {
            $cmd = "\"$mysqlPath\" --user=$dbUser $dbName < \"$fileTmp\"";
            exec("cmd /c $cmd", $output, $result);
            $message = $result === 0 ? "✅ Restore completed successfully." : "❌ Restore failed.";
        } else {
            $message = "❌ Invalid file upload.";
        }
    }
}
?>

<?php include("includes/header.php"); ?>

<div class="container mt-5">
    <h2 class="mb-4">🔄 Database Backup & Restore</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST">
        <button type="submit" name="backup" class="btn btn-success mb-3">📦 Backup Database</button>
    </form>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Choose .sql file to restore:</label>
            <input type="file" name="sql_file" accept=".sql" class="form-control" required>
        </div>
        <button type="submit" name="restore" class="btn btn-warning">♻️ Restore Database</button>
    </form>
</div>

<?php include("includes/footer.php"); ?>
