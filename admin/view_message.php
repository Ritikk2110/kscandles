<?php
// admin/view_message.php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    die('Invalid message ID.');
}

$stmt = $conn->prepare("SELECT * FROM contact_messages WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$message = $res->fetch_assoc();

if (!$message) {
    die('Message not found.');
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>View Message #<?= htmlspecialchars($id) ?></title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>



:root {
  --bg: linear-gradient(135deg, #f6f1eb, #fffaf3);
  --accent: #8b6f47;
  --accent-gradient: linear-gradient(135deg, #a88b5b, #d5b98b);
  --text-dark: #3b2f2f;
  --panel: rgba(255, 255, 255, 0.95);
  --shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
  --radius: 18px;
  --font-heading: 'Cormorant Garamond', serif;
  --font-body: 'Poppins', sans-serif;
}

body {
  margin: 0;
  padding: 40px 20px;
  font-family: var(--font-body);
  background: var(--bg);
  color: var(--text-dark);
  line-height: 1.4;
}

.container {
  max-width: 780px;
  margin: 40px auto;
  background: var(--panel);
  backdrop-filter: blur(10px);
  padding: 40px;
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  border: 1px solid rgba(139, 111, 71, 0.1);
}

h1 {
  font-family: var(--font-heading);
  font-size: 2rem;
  color: var(--accent);
  margin-bottom: 25px;
  letter-spacing: 0.5px;
  text-align: center;
  position: relative;
}
h1::after {
  content: "";
  display: block;
  width: 60px;
  height: 3px;
  background: var(--accent-gradient);
  margin: 10px auto 0;
  border-radius: 2px;
}

.info {
  margin-bottom: 16px;
  font-size: 1rem;
  display: flex;
  justify-content: flex-start;
  flex-wrap: wrap;
}
.info strong {
  width: 130px;
  color: #5b4636;
  font-weight: 600;
}
.info a {
  color: var(--accent);
  text-decoration: none;
}
.info a:hover {
  text-decoration: underline;
}

.message-box {
  margin-top: 25px;
  background: #fdfaf6;
  border: 1px solid #e5ded0;
  border-radius: 12px;
  padding: 20px;
  font-size: 1rem;
  color: #4a3b2a;
  line-height: 1.4;
  box-shadow: inset 0 2px 8px rgba(0,0,0,0.03);
  white-space: pre-wrap;
}

.actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  margin-top: 35px;
  flex-wrap: wrap;
}

.btn {
  padding: 10px 18px;
  border-radius: 10px;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  font-family: var(--font-body);
  border: none;
}

.btn.back {
  background: rgba(139, 111, 71, 0.08);
  color: var(--text-dark);
}
.btn.back:hover {
  background: rgba(139, 111, 71, 0.18);
}

.btn.delete {
  background: linear-gradient(135deg, #b24d4d, #8b2222);
  color: #fff;
  box-shadow: 0 4px 10px rgba(139,34,34,0.2);
}
.btn.delete:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(139,34,34,0.3);
}

@media (max-width: 600px) {
  .container {
    padding: 25px;
  }
  h1 {
    font-size: 1.6rem;
  }
  .info strong {
    width: 100%;
  }
  .actions {
    flex-direction: column;
    align-items: stretch;
  }
  .btn {
    width: 100%;
    justify-content: center;
  }
}

</style>
</head>
<body>
  <div class="container">
    <h1>Message Details</h1>

    <div class="info"><strong>ID:</strong> <?= htmlspecialchars($message['id']) ?></div>
    <div class="info"><strong>Name:</strong> <?= htmlspecialchars($message['name']) ?></div>
    <div class="info"><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($message['email']) ?>"><?= htmlspecialchars($message['email']) ?></a></div>
    <div class="info"><strong>Phone:</strong> <?= htmlspecialchars($message['phone']) ?></div>
    <div class="info"><strong>Subject:</strong> <?= htmlspecialchars($message['subject']) ?></div>
    <div class="info"><strong>Sent At:</strong> <?= htmlspecialchars($message['sent_at']) ?></div>

    <div class="message-box"><?= nl2br(htmlspecialchars($message['message'])) ?></div>

    <div class="actions">
      <a href="messages.php" class="btn back">← Back to Messages</a>
      <form method="post" action="delete_message.php" onsubmit="return confirm('Are you sure you want to delete this message?');">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] = bin2hex(random_bytes(32)) ?>">
        <input type="hidden" name="id" value="<?= (int)$message['id'] ?>">
        <button type="submit" class="btn delete">Delete Message</button>
      </form>
    </div>
  </div>
</body>
</html>
