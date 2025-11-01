<?php
// admin/messages.php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') {
    echo "<div class='alert success'>Message deleted successfully!</div>";
}


// Generate CSRF token (for delete actions)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Export CSV if requested
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $res = $conn->query("SELECT id, name, email, phone, subject, message, sent_at FROM contact_messages ORDER BY id DESC");
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=contact_messages_' . date('Ymd_His') . '.csv');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['ID','Name','Email','Phone','Subject','Message','Sent At']);
    while ($row = $res->fetch_assoc()) {
        $row['message'] = str_replace(["\r","\n"], [' ', ' '], $row['message']);
        fputcsv($out, [$row['id'], $row['name'], $row['email'], $row['phone'], $row['subject'], $row['message'], $row['sent_at']]);
    }
    fclose($out);
    exit;
}

// Fetch messages
$res = $conn->query("SELECT id, name, email, phone, subject, message, sent_at FROM contact_messages ORDER BY id DESC");
$messages = $res->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Contact Messages - Admin</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  
  <style>
   


   /* =======================================
   Candle Haven - Admin Messages Theme
   Palette: Creamy White + Warm Candle Gold (#b68d50)
   Font: 'Poppins', sans-serif
========================================== */

:root {
  --candle-gold: #b68d50;
  --cream-bg: #fdfaf5;
  --soft-panel: #fffaf0;
  --muted-text: #6e5e42;
  --text-dark: #2c2419;
  --border: #e9dcc3;
  --hover-bg: #f6eee3;
}

/* Reset + Base */
* {
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  margin: 0;
  padding: 24px;
  background: linear-gradient(135deg, #fdfaf5, #f8f3ea);
  color: var(--text-dark);
}

/* ===== HEADER ===== */
header.admin-top {
  display: flex;
  gap: 12px;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 22px;
  background: linear-gradient(135deg, #b68d50, #cdb78f);
  padding: 16px 24px;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(182, 141, 80, 0.25);
  color: #fff;
}

.top-left h1 {
  font-size: 1.5rem;
  margin: 0;
  color: #fff;
}

.top-left .small-muted {
  color: rgba(255, 255, 255, 0.85);
  font-size: 0.9rem;
}

.top-actions {
  display: flex;
  gap: 14px;
  align-items: center;
  flex-wrap: wrap;
}

/* Buttons */
.btn {
  padding: 10px 16px;
  border-radius: 10px;
  font-weight: 600;
  text-decoration: none;
  border: none;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  transition: all 0.3s ease;
}

.btn.primary {
  background: linear-gradient(135deg, #b68d50, #d7c29a);
  color: #fff;
  box-shadow: 0 4px 12px rgba(182, 141, 80, 0.4);
}

.btn.primary:hover {
  background: linear-gradient(135deg, #a67b35, #c9a769);
  transform: translateY(-2px);
}

.btn.ghost {
  background: transparent;
  color: var(--text-dark);
  border: 1px solid var(--border);
}

.btn.ghost:hover {
  background: var(--hover-bg);
}

/* Delete Button */
.btn-delete {
  background: #e85b5b;
  color: #fff;
}

.btn-delete:hover {
  background: #c94444;
}

/* ===== PANEL ===== */
.panel {
  background: var(--soft-panel);
  border-radius: 14px;
  padding: 18px;
  box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
  overflow: auto;
}

/* Search + Controls */
.controls {
  display: flex;
  gap: 10px;
  align-items: center;
}

.search-input {
  padding: 10px 12px;
  border-radius: 10px;
  border: 1px solid var(--border);
  width: 260px;
  background: #fff;
  color: var(--text-dark);
}

.search-input:focus {
  outline: none;
  border-color: var(--candle-gold);
  box-shadow: 0 0 0 3px rgba(182, 141, 80, 0.2);
}

/* ===== TABLE ===== */
.table-wrap {
  margin-top: 20px;
  width: 100%;
  overflow: auto;
}

table.messages-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 900px;
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 3px 18px rgba(0, 0, 0, 0.06);
}

.messages-table thead th {
  text-align: left;
  padding: 12px 14px;
  background: linear-gradient(90deg, #fdf5e6, #faedd3);
  color: var(--text-dark);
  font-size: 0.9rem;
  letter-spacing: 0.5px;
  border-bottom: 1px solid var(--border);
}

.messages-table tbody tr {
  border-bottom: 1px solid #f0e6d2;
  transition: all 0.25s ease;
}

.messages-table tbody tr:hover {
  background: var(--hover-bg);
  transform: translateY(-1px);
}

.messages-table td {
  padding: 12px 14px;
  vertical-align: top;
  font-size: 0.95rem;
  color: var(--text-dark);
}

.msg-subject {
  font-weight: 600;
  color: var(--candle-gold);
  margin-bottom: 4px;
}

.msg-body {
  color: var(--muted-text);
  font-size: 0.9rem;
  white-space: pre-wrap;
  max-width: 560px;
}

/* Pills (for phone display) */
.pill {
  display: inline-block;
  padding: 6px 10px;
  border-radius: 999px;
  background: rgba(182, 141, 80, 0.15);
  color: var(--text-dark);
  font-size: 0.85rem;
  margin-right: 6px;
}

/* ===== Empty State ===== */
.empty-state {
  text-align: center;
  padding: 30px;
  color: var(--muted-text);
  font-style: italic;
}

/* ===== Alerts ===== */
.alert.success {
  background: linear-gradient(135deg, #fff7e6, #fbe7c6);
  color: #5f4b23;
  padding: 10px 20px;
  border-radius: 10px;
  text-align: center;
  font-weight: 500;
  box-shadow: 0 4px 10px rgba(182, 141, 80, 0.2);
  margin-bottom: 20px;
}

/* ===== Responsive ===== */
@media (max-width: 920px) {
  .search-input {
    width: 180px;
  }
  table.messages-table {
    min-width: 700px;
  }
}

@media (max-width: 640px) {
  header.admin-top {
    flex-direction: column;
    align-items: stretch;
    gap: 12px;
  }
  .controls {
    justify-content: space-between;
  }
  table.messages-table {
    min-width: 600px;
  }
}


  </style>
</head>
<body>

<header class="admin-top">
  <div class="top-left">
    <h1>Contact Messages</h1>
    <div class="small-muted">All user messages submitted via Contact form</div>
  </div>

  <div class="top-actions">
    <div class="controls">
      <input id="searchBox" class="search-input" placeholder="Search name / email / phone..." />
      <button class="btn ghost" onclick="clearSearch()">Clear</button>
    </div>
    <div style="display:flex; gap:10px; margin-left:12px;">
      <a href="messages.php?export=csv" class="btn primary" title="Export CSV">Export CSV</a>
      <a href="index.php" class="btn ghost">Back to Dashboard</a>
    </div>
  </div>
</header>

<main class="panel">
  <?php if (empty($messages)): ?>
    <div class="empty-state">
      <strong>No messages found.</strong><br>
      There are no contact messages yet.
    </div>
  <?php else: ?>

    <div class="table-wrap">
      <table class="messages-table" id="messagesTable">
        <thead>
          <tr>
            <th style="width:70px">ID</th>
            <th>Name / Contact</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Message</th>
            <th style="width:170px">Sent At</th>
            <th style="width:120px">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($messages as $m): ?>
            <tr>
              <td><?= (int)$m['id'] ?></td>
              <td>
                <div class="msg-subject"><?= htmlspecialchars($m['name']) ?></div>
                <div class="small-muted"><span class="pill"><?= htmlspecialchars($m['phone'] ?: '—') ?></span></div>
              </td>
              <td><a href="mailto:<?= htmlspecialchars($m['email']) ?>"><?= htmlspecialchars($m['email']) ?></a></td>
              <td><?= htmlspecialchars($m['subject'] ?: 'Contact') ?></td>
              <td><div class="msg-body"><?= nl2br(htmlspecialchars($m['message'])) ?></div></td>
              <td class="small-muted"><?= htmlspecialchars($m['sent_at']) ?></td>
              <td>
                <a class="btn ghost" href="view_message.php?id=<?= (int)$m['id'] ?>">View</a>

                <!-- Secure delete form -->
                <form action="delete_message.php" method="POST" style="display:inline;" onsubmit="return confirm('Delete this message?');">
                  <input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
                  <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                  <button type="submit" class="btn btn-delete">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  <?php endif; ?>
</main>

<script>
const searchBox = document.getElementById('searchBox');
searchBox.addEventListener('input', () => {
  const q = searchBox.value.trim().toLowerCase();
  const rows = document.querySelectorAll('#messagesTable tbody tr');
  rows.forEach(r => {
    const text = r.innerText.toLowerCase();
    r.style.display = text.includes(q) ? '' : 'none';
  });
});
function clearSearch(){ searchBox.value=''; searchBox.dispatchEvent(new Event('input')); }
</script>

</body>
</html>
