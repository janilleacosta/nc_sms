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
<style>
    .card{
        margin-left: 400px;
    }

    .bg-video { position:fixed; top:0; left:0; min-width:100%; min-height:100%; object-fit:cover; z-index:-1; }

</style>
</head>
<body>

    <div class="navbar-brand mb-0 h1">Recent Activity</div>
    <a href="index.php" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
</nav>

<div class="d-flex">
<video autoplay muted loop playsinline class="bg-video">
    <source src="videoplayback.mp4" type="video/mp4">
</video>

    <nav class="sidebar" id="sidebar">
        <div class="sidebar-profile text-center mb-4">
            <a href="profile.php" class="text-white text-decoration-none">
                <img src="iconnn.jpg" alt="Profile" class="rounded-circle mb-2" width="80" height="80">
                <div class="fw-bold">Admin</div>
            </a>
        </div>
        <ul class="nav flex-column">
            <li><a class="nav-link" href="dashboard.php">Dashboard</a></li>
            <li><a class="nav-link" href="addStudentForm.php">Add Student</a></li>
            <li><a class="nav-link" href="viewAllStudent.php">View All Student</a></li>
            <li><a class="nav-link active" href="recentAdd.php">Recently Added</a></li>
        </ul>
        <hr style="border-color: rgba(255,255,255,0.3);">
        <a href="logout.php" class="btn btn-outline-light btn-sm logout-btn d-block mt-3">Logout</a>
    </nav>

    <!-- Your recent students table remains here... -->
<div class="card shadow-sm mb-4">
        <div class="card-header bg-dark"><h2 class="h5 mb-0">Recently Added Students</h2></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered text-white mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Course</th>
                            <th>Department</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($recentStudents): ?>
                            <?php foreach($recentStudents as $s): ?>
                                <tr>
                                    <td><?= htmlspecialchars((string)$s['id']) ?></td>
                                    <td><?= htmlspecialchars($s['last_name'] . ', ' . $s['first_name']) ?></td>
                                    <td><?= htmlspecialchars($s['course']) ?></td>
                                    <td><?= htmlspecialchars($s['department']) ?></td>
                                    <td><?= htmlspecialchars($s['created_at']) ?></td>
                                    <td>
                                        <a href="viewStudentDetails.php?id=<?= (int)$s['id'] ?>" class="btn btn-sm btn-info">View</a>
                                        <a href="editStudentForm.php?id=<?= (int)$s['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="deleteStudent.php?id=<?= (int)$s['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center py-3">No Students found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

                <div class="table-responsive">
                 </main>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
