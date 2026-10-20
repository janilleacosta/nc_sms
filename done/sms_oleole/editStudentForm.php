<?php 
require 'dbconn.php';

/* VALIDATE ID */
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
} elseif (isset($_GET['student_id']) && is_numeric($_GET['student_id'])) {
    $id = (int)$_GET['student_id'];
} else {
    die('Invalid Student ID.');
}

/* FETCH STUDENT */
$sql = "SELECT * FROM students_info_tbl WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student){
    die('Student not found.');
}

/* IMPORTANT FIX:
   Add student_id index so your old code still works */
$student['student_id'] = $student['id'];
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Student Registration Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        main { padding-top: 2rem; padding-bottom: 2rem; }
        .d-flex { min-height: 100vh; }

        .sidebar {
            width: 220px;
            background-color: #198754;
            color: white;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 1030;
        }

        @media (max-width: 767.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
        }

        .sidebar .nav-link {
            color: white;
            padding: 8px 12px;
            display: block;
            border-radius: 4px;
            margin-bottom: 5px;
            text-decoration: none;
        }

        .sidebar .nav-link:hover { background-color: rgba(255,255,255,0.2); }
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.3);
            font-weight: bold;
        }

        .sidebar .logout-btn { margin-top: 20px; width: 100%; }

        main {
            margin-left: 250px;
            padding: 40px;
        }

        @media (max-width: 991px) {
            main { margin-left: 0; padding: 20px; }
        }

        #sidebarToggle {
            background-color: #198754;
            color: white;
            border: none;
            padding: 8px 12px;
            font-size: 18px;
            border-radius: 4px;
        }

        .sidebar-profile img {
            border: 2px solid #fff;
            object-fit: cover;
        }

        .sidebar-profile div {
            font-size: 16px;
            margin-bottom: 2px;
        }

        .sidebar-profile small {
            font-size: 12px;
            opacity: 0.8;
        }

        .sidebar-profile a:hover {
            text-decoration: none;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="d-flex">

<nav class="sidebar" id="sidebar">
    <div class="sidebar-profile text-center mb-4">
        <a href="profile.php" class="text-white text-decoration-none">
            <img src="nclogo1.png" alt="Profile" class="rounded-circle mb-2" width="80" height="80">
            <div class="fw-bold">eme ka te?</div>
            <small>View Profile</small>
        </a>
    </div>

    <ul class="nav flex-column">
        <li><a class="nav-link" href="index.php">Dashboard</a></li>
        <li><a class="nav-link" href="addStudentForm.php">Add Student</a></li>
        <li><a class="nav-link" href="viewAllStudent.php">View All Student</a></li>
    </ul>

    <hr style="border-color: rgba(255,255,255,0.3);">
    <a href="logout.php" class="btn btn-outline-light btn-sm logout-btn d-block mt-3">Logout</a>
</nav>

<main>
<section class="container">
<form class="row g-3 needs-validation" novalidate id="updateForm" action="editStudent.php" method="POST">

<input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student['student_id']); ?>">

<h2>Student Update Form</h2>

<div class="col-md-4">
<label class="form-label">Last Name</label>
<input type="text" class="form-control" name="last_name" required value="<?php echo htmlspecialchars($student['last_name']); ?>">
</div>

<div class="col-md-4">
<label class="form-label">First Name</label>
<input type="text" class="form-control" name="first_name" required value="<?php echo htmlspecialchars($student['first_name']); ?>">
</div>

<div class="col-md-2">
<label class="form-label">M.I.</label>
<input type="text" class="form-control" name="middle_initial" value="<?php echo htmlspecialchars($student['middle_initial'] ?? ''); ?>">
</div>

<div class="col-md-2">
<label class="form-label">Extension</label>
<input type="text" class="form-control" name="extension_name" value="<?php echo htmlspecialchars($student['extension_name'] ?? ''); ?>">
</div>

<div class="col-md-2">
<label class="form-label">Age</label>
<input type="number" class="form-control" name="age" required value="<?php echo htmlspecialchars($student['age']); ?>">
</div>

<div class="col-md-4">
<label class="form-label">Birthdate</label>
<input type="date" class="form-control" name="birthdate" required value="<?php echo htmlspecialchars($student['birthdate']); ?>">
</div>

<div class="col-md-4">
<label class="form-label">Street</label>
<input type="text" class="form-control" name="street" required value="<?php echo htmlspecialchars($student['street']); ?>">
</div>

<div class="col-md-4">
<label class="form-label">Barangay</label>
<input type="text" class="form-control" name="barangay" required value="<?php echo htmlspecialchars($student['barangay']); ?>">
</div>

<div class="col-md-4">
<label class="form-label">City</label>
<input type="text" class="form-control" name="city" required value="<?php echo htmlspecialchars($student['city']); ?>">
</div>

<div class="col-md-4">
<label class="form-label">Province</label>
<input type="text" class="form-control" name="province" required value="<?php echo htmlspecialchars($student['province']); ?>">
</div>

<div class="col-md-4">
<label class="form-label">Zip Code</label>
<input type="number" class="form-control" name="zip_code" required value="<?php echo htmlspecialchars($student['zip_code']); ?>">
</div>

<div class="col-md-6">
<label class="form-label">Course</label>
<select class="form-select" name="course" required>
<option value="BSIT" <?= ($student['course']=='BSIT')?'selected':''; ?>>BS Information Technology</option>
<option value="BSCS" <?= ($student['course']=='BSCS')?'selected':''; ?>>BS Computer Science</option>
<option value="BSECE" <?= ($student['course']=='BSECE')?'selected':''; ?>>BS Electronics Engineering</option>
<option value="BSED" <?= ($student['course']=='BSED')?'selected':''; ?>>Bachelor of Secondary Education</option>
</select>
</div>

<div class="col-md-6">
<label class="form-label">Department</label>
<select class="form-select" name="department" required>
<option value="CCS" <?= ($student['department']=='CCS')?'selected':''; ?>>College of Computer Sciences</option>
<option value="COE" <?= ($student['department']=='COE')?'selected':''; ?>>College of Engineering</option>
<option value="COED" <?= ($student['department']=='COED')?'selected':''; ?>>College of Education</option>
</select>
</div>

<div class="col-12">
<button type="submit" class="btn btn-primary">Update</button>
<button type="reset" class="btn btn-secondary">Reset</button>
</div>

</form>
</section>
</main>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
