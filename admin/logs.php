<?php
session_start();
require_once "../db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// logs fetch
$result = $conn->query("SELECT * FROM logs ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Logs - SCANME</title>
</head>
<body>

<h2>System Logs</h2>

<a href="index.php">Back to Dashboard</a>

<table border="1" cellpadding="10" cellspacing="0">
<tr>
    <th>ID</th>
    <th>Admin/User ID</th>
    <th>Action</th>
    <th>Description</th>
    <th>IP Address</th>
    <th>Created At</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['user_id'] ?></td>
    <td><?= $row['action'] ?></td>
    <td><?= $row['description'] ?></td>
    <td><?= $row['ip_address'] ?></td>
    <td><?= $row['created_at'] ?></td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>
