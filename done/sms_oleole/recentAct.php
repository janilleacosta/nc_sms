<?php
declare(strict_types=1);
session_start();
require 'dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$isAdmin = ($_SESSION['role'] ?? '') === 'admin';
$userId  = (int)($_SESSION['user_id'] ?? 0);

$stmt = $conn->query("SELECT system_name FROM system_settings LIMIT 1");
$sys = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch recent student activities (last 50)
try {
    $sql = "SELECT id, last_name, first_name, course, department, created_at FROM students_info_tbl";
    if (!$isAdmin) {
        $sql .= " WHERE user_id = ?";
        $stmt = $conn->prepare($sql . " ORDER BY created_at DESC LIMIT 50");
        $stmt->execute([$userId]);
    } else {
        $stmt = $conn->prepare($sql . " ORDER BY created_at DESC LIMIT 50");
        $stmt->execute();
    }
    $recentStudents = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $recentStudents = [];
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($sys['system_name']) ?> - Recent Activity</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark px-4 d-flex justify-content-between align-items-center">
    <div class="navbar-brand mb-0 h1">Recent Activity</div>
    <a href="index.php" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="d-flex">
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-profile text-center mb-4">
            <a href="profile.php" class="text-white text-decoration-none">
                <img src="iconnn.jpg" alt="Profile" class="rounded-circle mb-2" width="80" height="80">
                <div class="fw-bold">Admin</div>
                <small>View Profile</small>
            </a>
        </div>
        <ul class="nav flex-column">
            <li><a class="nav-link" href="dashboard.php">Dashboard</a></li>
            <li><a class="nav-link" href="addStudentForm.php">Add Student</a></li>
            <li><a class="nav-link" href="viewAllStudent.php">View All Student</a></li>
 <li><a class="nav-link" href="recentAdd.php">Recently Added</a></li>        </ul>
        <hr style="border-color: rgba(255,255,255,0.3);">
        <a href="logout.php" class="btn btn-outline-light btn-sm logout-btn d-block mt-3">Logout</a>
    </nav>

    <main class="flex-fill p-4">
        <div class="page-header mb-3">
            <h1 class="h3 mb-1">Recent Activity</h1>
            <p class="text-muted mb-0">Overview of the last 50 student activities</p>
        </div>

        <div class="card bg-dark text-white shadow-sm">
            <div class="card-header bg-secondary">
                <h5 class="mb-0">Student Activities</h5>
            </div>
            <div class="card-body p-2" style="max-height: 500px; overflow-y:auto;">
                <ul class="list-group list-group-flush" id="notifList">
                    <?php if($recentStudents): ?>
                        <?php foreach($recentStudents as $s): ?>
                            <li class="list-group-item bg-dark text-white d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="bi bi-person-fill me-2"></i>
                                    <?= htmlspecialchars($s['first_name'].' '.$s['last_name']) ?> added to <?= htmlspecialchars($s['course']) ?>
                                </span>
                                <small class="text-muted"><?= date('M d, Y H:i', strtotime($s['created_at'])) ?></small>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item bg-dark text-white text-center">No recent activity.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </main>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
