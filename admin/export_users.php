
<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// ✅ Restrict access to admin only
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// ✅ Set headers to force download as CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=users_export_' . date('Y-m-d_H-i-s') . '.csv');

// ✅ Open output stream
$output = fopen('php://output', 'w');

// ✅ Write CSV headers
fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Address', 'Created At']);

// ✅ Fetch all users<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// ✅ Restrict access to admin only
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// ✅ Set headers to force download as CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=users_export_' . date('Y-m-d_H-i-s') . '.csv');

// ✅ Open output stream
$output = fopen('php://output', 'w');

// ✅ Write CSV headers
fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Address', 'Created At']);

// ✅ Fetch all users
$query = "SELECT id, name, email, phone, address, created_at FROM users ORDER BY id DESC";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Clean output and write each row to CSV
        fputcsv($output, [
            $row['id'],
            $row['name'],
            $row['email'],
            $row['phone'],
            $row['address'],
            $row['created_at']
        ]);
    }
} else {
    // If no records found, write a message row
    fputcsv($output, ['No users found']);
}

// ✅ Close output
fclose($output);
exit();



?>
