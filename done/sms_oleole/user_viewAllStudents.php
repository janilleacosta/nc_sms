<?php
require 'dbconn.php';

$course_filter = isset($_GET['course']) ? $_GET['course'] : '';
$courses = ['BSIT', 'BSCS', 'BSECE', 'BSED'];

$column_id = 'student_id';

if ($course_filter && in_array($course_filter, $courses)) {
    $sql = "SELECT * FROM students_info_tbl WHERE course = :course ORDER BY $column_id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':course' => $course_filter]);
} else {
    $sql = "SELECT * FROM students_info_tbl ORDER BY $column_id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>All Students</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

<style>
body {
    background-color: #121212;
    color: #fff;
    min-height: 100vh;
}
main {
    margin-top: 0px;
    margin-left: 400px;
    padding: 20px 40px 40px 40px; 
    display: flex;
    flex-direction: column;
    align-items: center;
}
h2 {
    margin-top: 0;
    text-align: center;
}
.course-btn {
    margin: 5px;
}
.course-btn.active {
    background-color: #198754 !important;
    box-shadow: 0 0 10px #00ff88;
}
.card-table {
    margin-left: 0px;
    background-color: #1e1e1e;
    border-radius: 10px;
    box-shadow: 0 0 20px #00ff88;
    width: 100%;
}
.table thead th {
    background-color: #198754;
    color: white;
    text-align: center;
}
.table tbody tr:hover {
    background-color: rgba(25,135,84,0.2);
}
.btn-action {
    font-weight: bold;
    border: none;
}
.btn-action.edit {
    background-color: #198754;
    color: white;
}
.btn-action.edit:hover {
    box-shadow: 0 0 10px #00ff88;
}
.bg-video { 
    position:fixed; 
    top:0; 
    left:0; 
    min-width:100%; 
    min-height:100%; 
    object-fit:cover; 
    z-index:-1; 
}
@media (max-width: 991px) {
    main {
        margin-left: 0;
        padding: 20px;
    }
}
</style>
</head>

<body>

<div class="d-flex">
<video autoplay muted loop playsinline class="bg-video">
    <source src="videoplayback.mp4" type="video/mp4">
</video>

<nav class="sidebar">
    <div class="text-center mb-4">
        <img src="iconnn.jpg" class="rounded-circle mb-2" width="80" height="80">
        <div class="fw-bold">User</div>
    </div>

    <ul class="nav flex-column">
        <li><a class="nav-link" href="user_dashboard.php">Dashboard</a></li>
        <li><a class="nav-link active" href="user_viewAllStudents.php">View All Students</a></li>
    </ul>
    <hr style="border-color: rgba(255,255,255,0.3);">
    <a href="logout.php" class="btn btn-outline-light btn-sm logout-btn d-block mt-3">Logout</a>
</nav>

<main>

<h2 class="mb-3 text-dark">All Students</h2>

<div class="mb-3 text-center">
    <a href="user_viewAllStudents.php"
       class="btn <?= $course_filter === '' ? 'btn-primary active' : 'btn-secondary' ?> course-btn">
       All Courses
    </a>

    <?php foreach ($courses as $course): ?>
        <a href="user_viewAllStudents.php?course=<?= $course ?>"
           class="btn <?= ($course_filter === $course) ? 'btn-primary active' : 'btn-secondary' ?> course-btn">
           <?= $course ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="card card-table p-3">
    <div class="table-responsive">
        <table class="table table-bordered text-white align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Course</th>
                    <th>Department</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($students) > 0): ?>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['student_id']) ?></td>
                            <td><?= htmlspecialchars($student['last_name']) ?></td>
                            <td><?= htmlspecialchars($student['first_name']) ?></td>
                            <td><?= htmlspecialchars($student['course']) ?></td>
                            <td><?= htmlspecialchars($student['department']) ?></td>
                            <td>
                                <a href="editStudentForm.php?student_id=<?= $student['student_id']; ?>" 
                                   class="btn btn-sm btn-action edit">
                                   Edit
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No students found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</main>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
