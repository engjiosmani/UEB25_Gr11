<?php
require_once 'db_conn.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $checkRoleQuery = "SELECT role FROM users WHERE id = ?";
    $stmtCheck = $conn->prepare($checkRoleQuery);
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck && $resultCheck->num_rows > 0) {
        $user = $resultCheck->fetch_assoc();
        if (strtolower($user['role']) !== 'admin') {
            $deleteQuery = "DELETE FROM users WHERE id = ?";
            $stmt = $conn->prepare($deleteQuery);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                header("Location: delete_user.php?deleted=true");
                exit();
            }
        }
    }
}


$successMessage = '';
if (isset($_GET['deleted']) && $_GET['deleted'] == 'true') {
    $successMessage = "<div class='alert alert-success'>User deleted successfully.</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Festava</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    body {
      background: linear-gradient(135deg, #f5cf97, #ff6126);
      font-family: 'Arial', sans-serif;
      min-height: 100vh;
    }
    .sidebar {
      background-color: #111;
      padding: 20px;
      min-height: 100vh;
      color: white;
    }
    .sidebar h2 {
      color: #fecd1a;
      font-weight: bold;
      margin-bottom: 30px;
    }
    .sidebar a {
      display: block;
      padding: 10px;
      color: white;
      text-decoration: none;
      margin-bottom: 10px;
      border-radius: 5px;
    }
    .sidebar a:hover, .sidebar a.active {
      background-color: #f2541b;
    }
    .main-content {
      padding: 30px;
      background-color: #fff;
      border-radius: 20px;
      margin: 20px;
      box-shadow: 0 8px 20px rgba(255, 84, 27, 0.3);
    }
    .message-box {
      background-color: #222;
      color: white;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-3 sidebar">
      <h2>Admin Panel</h2>
      <a href="#messages" class="active"><i class="bi bi-envelope"></i> Messages</a>
      <a href="#users"><i class="bi bi-people"></i> Users</a>
      <a href="#tickets"><i class="bi bi-ticket"></i> Tickets</a>
      <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>

    <div class="col-md-9">

      <?php if (!empty($successMessage)) echo $successMessage; ?>

      <div class="main-content" id="messages">
        <h3 class="text-warning mb-4"><i class="bi bi-envelope-fill"></i> Contact Messages</h3>
        <?php
        $msgQuery = "SELECT name, email, company, message, created_at FROM contact_messages ORDER BY created_at DESC";
        $msgResult = $conn->query($msgQuery);
        if ($msgResult && $msgResult->num_rows > 0) {
          while ($row = $msgResult->fetch_assoc()) {
            echo "<div class='message-box'>";
            echo "<h5>From: " . htmlspecialchars($row['name']) . "</h5>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
            echo "<p><strong>Company:</strong> " . htmlspecialchars($row['company']) . "</p>";
            echo "<p><strong>Message:</strong> " . nl2br(htmlspecialchars($row['message'])) . "</p>";
            echo "<p class='text-muted' style='font-size: 0.85em;'>Sent at: " . $row['created_at'] . "</p>";
            echo "</div>";
          }
        } else {
          echo "<p class='text-muted'>No messages found.</p>";
        }
        ?>
      </div>

      <div class="main-content" id="users">
        <h3 class="text-warning mb-4"><i class="bi bi-people-fill"></i> Registered Users</h3>
        <?php
        $userQuery = "SELECT id, fullname, email, dob, role FROM users ORDER BY id DESC";
        $userResult = $conn->query($userQuery);
        if ($userResult && $userResult->num_rows > 0) {
          echo "<table class='table table-striped'>";
          echo "<thead class='table-dark'><tr><th>Full Name</th><th>Email</th><th>Date of Birth</th><th>Role</th><th>Action</th></tr></thead><tbody>";
          while ($user = $userResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($user['fullname']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td>" . $user['dob'] . "</td>";
            echo "<td>" . ucfirst($user['role']) . "</td>";
            if (strtolower($user['role']) !== 'admin') {
              echo "<td><a href='?id=" . $user['id'] . "' onclick=\"return confirm('Are you sure you want to delete this user?')\" class='btn btn-danger btn-sm'>Delete</a></td>";
            } else {
              echo "<td><span class='text-muted'>-</span></td>";
            }
            echo "</tr>";
          }
          echo "</tbody></table>";
        } else {
          echo "<p class='text-muted'>No users found.</p>";
        }
        ?>
      </div>

      <div class="main-content" id="tickets">
        <h3 class="text-warning mb-4"><i class="bi bi-ticket-perforated-fill"></i> Purchased Tickets</h3>
        <?php
        $ticketQuery = "SELECT u.fullname AS user_name, t.ticket_type, t.num_tickets, t.phone, t.total_price
                        FROM tickets t
                        JOIN users u ON t.user_id = u.id
                        ORDER BY t.id DESC";
        $ticketResult = $conn->query($ticketQuery);
        if ($ticketResult && $ticketResult->num_rows > 0) {
          echo "<table class='table table-striped'>";
          echo "<thead class='table-dark'><tr><th>User</th><th>Type</th><th>Tickets</th><th>Phone</th><th>Total</th></tr></thead><tbody>";
          while ($row = $ticketResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['user_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['ticket_type']) . "</td>";
            echo "<td>" . htmlspecialchars($row['num_tickets']) . "</td>";
            echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
            echo "<td>$" . number_format($row['total_price'], 2) . "</td>";
            echo "</tr>";
          }
          echo "</tbody></table>";
        } else {
          echo "<p class='text-muted'>No tickets purchased yet.</p>";
        }
        ?>
      </div>

    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
