<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($_SESSION['admin_id'])) { http_response_code(403); exit; }

$rows = $conn->query("
  SELECT DATE_FORMAT(order_date, '%Y-%m') AS ym, IFNULL(SUM(total),0) AS revenue
  FROM orders GROUP BY ym ORDER BY ym DESC LIMIT 12
")->fetch_all(MYSQLI_ASSOC);

$labels = []; $values = [];
foreach (array_reverse($rows) as $r) { $labels[] = $r['ym']; $values[] = (float)$r['revenue']; }
header('Content-Type: application/json');
echo json_encode(['labels'=>$labels,'values'=>$values]);
