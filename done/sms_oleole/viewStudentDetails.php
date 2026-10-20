<?php
require 'dbconn.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student = null;

if ($id > 0) {
    try {
        $stmt = $conn->prepare("SELECT * FROM students_info_tbl WHERE id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $student = null;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Details - Student LMS</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body>
<main class="container my-4">
    <h1 class="h3">Student Details</h1>
    <?php if ($student): ?>
        <div class="card">
            <div class="card-body">
                <p><strong>ID:</strong> <?php echo htmlspecialchars($student['id']); ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($student['last_name'] . ', ' . $student['first_name']); ?></p>
                <p><strong>Course:</strong> <?php echo htmlspecialchars($student['course']); ?></p>
                <p><strong>Department:</strong> <?php echo htmlspecialchars($student['department']); ?></p>
                <p><strong>Created At:</strong> <?php echo htmlspecialchars($student['created_at']); ?></p>
            </div>
        </div>
    <?php else: ?>
        <p class="text-danger">Student not found.</p>
    <?php endif; ?>
    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</main>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>