<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Student Registration Form</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
    body { 
        background-color: #121212; 
        color: #fff; 
    }
    .d-flex { 
        min-height: 100vh; 
    }
    main { 
        margin-top: 0px;
        margin-left: 250px; 
        padding: 40px; 
    }
@media (max-width: 991px) { main { margin-left: 0; padding: 20px; } }

/* Transparent Light Container */
.container {
    background: rgba(255, 255, 255, 0.15); /* made more transparent */
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 50px 40px 40px 40px;
    border-radius: 15px;
    width: 100%;
    max-width: 380px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3); /* slightly lighter shadow for transparency */
    border: 1px solid rgba(255,255,255,0.2); /* lighter border to match transparency */
    position: relative;
}


.card.form-card { background-color: #1e1e1e; border-radius: 0.5rem; }
.card.form-card label { color: #ccc; }
.card.form-card input, .card.form-card select { 
    background-color: #2b2b2b; 
    color: #fff; 
    border: 1px solid #444; }
.card.form-card input:focus, .card.form-card select:focus { 
    border-color: #0d6efd; 
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25); 
}
.card.form-card legend { 
    color: #fff; 
    font-size: 1.2rem; 
}
#sidebarToggle { 
    background-color: #198754; 
    color: white; 
    border: none; 
    padding: 8px 12px; 
    font-size: 18px; 
    border-radius: 4px; 
    margin-bottom: 20px; 
}
.bg-video { position:fixed; top:0; left:0; min-width:100%; min-height:100%; object-fit:cover; z-index:-1; }
.invalid-feedback { 
    color: #ff6b6b; 
}
</style>
</head>

<body>
<div class="d-flex">

<video autoplay muted loop playsinline class="bg-video">
    <source src="videoplayback.mp4" type="video/mp4">
</video>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-profile text-center mb-4">
        <a href="profile.php" class="text-white text-decoration-none">
            <img src="iconnn.jpg" alt="Profile" class="rounded-circle" width="80" height="80">
        <div class="fw-bold">Admin</div>
        </a>
    </div>
    <ul class="nav flex-column">
        <li><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li><a class="nav-link active" href="addStudentForm.php">Add Student</a></li>
        <li><a class="nav-link" href="viewAllStudent.php">View All Student</a></li>
         <li><a class="nav-link" href="recentAdd.php">Recently Added</a></li>
    </ul>
    <hr style="border-color: rgba(255,255,255,0.3);">
    <a href="logout.php" class="btn btn-outline-light btn-sm logout-btn d-block mt-3">Logout</a>
</nav>

<!-- Main Content -->
<main>
    <button id="sidebarToggle" class="d-md-none">☰ Menu</button>
    <div class="page-header mb-4">
        <h1 class="h3 text-primary">Student Registration</h1>
        <p class="text-dark">Fill out the form below to add a new student</p>
    </div>

    <div class="card form-card shadow-sm p-4">
        <form class="row g-3 needs-validation" novalidate action="addStudent.php" method="POST">

            <!-- Personal Information -->
            <fieldset class="col-12">
                <legend class="h5 mb-3">Personal Information</legend>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name" required>
                        <div class="invalid-feedback">Please enter last name.</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name" required>
                        <div class="invalid-feedback">Please enter first name.</div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">M.I</label>
                        <input type="text" class="form-control" name="middle_initial" maxlength="2">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Extension</label>
                        <input type="text" class="form-control" name="extension_name" placeholder="Jr., III">
                    </div>
                </div>
            </fieldset>

            <!-- Demographic Details -->
            <fieldset class="col-12 mt-4">
                <legend class="h5 mb-3">Demographic Details</legend>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Age</label>
                        <input type="number" class="form-control" id="age" name="age" readonly required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Birthdate</label>
                        <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                    </div>
                </div>
            </fieldset>

            <!-- Contact Information -->
            <fieldset class="col-12 mt-4">
                <legend class="h5 mb-3">Contact Information</legend>
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Street</label><input type="text" class="form-control" name="street" required></div>
                    <div class="col-md-4"><label class="form-label">Barangay</label><input type="text" class="form-control" name="barangay" required></div>
                    <div class="col-md-4"><label class="form-label">City</label><input type="text" class="form-control" name="city" required></div>
                    <div class="col-md-4"><label class="form-label">Province</label><input type="text" class="form-control" name="province" required></div>
                    <div class="col-md-2"><label class="form-label">Zipcode</label><input type="number" class="form-control" name="zip_code" required></div>
                </div>
            </fieldset>

            <!-- Academic Information -->
            <fieldset class="col-12 mt-4">
                <legend class="h5 mb-3">Academic Information</legend>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Course</label>
                        <select class="form-select" id="course" name="course" required>
                            <option value="" selected disabled>Choose course...</option>
                            <option value="BSIT">BS Information Technology</option>
                            <option value="BSCS">BS Computer Science</option>
                            <option value="BSECE">BS Electronics Engineering</option>
                            <option value="BSED">BS Secondary Education</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Department</label>
                        <select class="form-select" id="department" name="department" required>
                            <option value="" selected disabled>Choose department...</option>
                            <option value="CIT">College of IT</option>
                            <option value="CCS">College of CS</option>
                            <option value="COE">College of Engineering</option>
                            <option value="COED">College of Education</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <!-- Buttons -->
            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-success" name="add_student" href="dashboard.php">Submit</button>
                <button type="reset" class="btn btn-secondary ms-2">Reset</button>

            </div>
        </form>
    </div>
</main>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form){
        form.addEventListener('submit', function(event){
            if(!form.checkValidity()){
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

const birthdateInput = document.getElementById('birthdate');
const ageInput = document.getElementById('age');
const courseSelect = document.getElementById('course');
const departmentSelect = document.getElementById('department');

birthdateInput.addEventListener('change', function () {
    const birthdate = new Date(this.value);
    const today = new Date();
    let age = today.getFullYear() - birthdate.getFullYear();
    const monthDiff = today.getMonth() - birthdate.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) age--;
    ageInput.value = age >= 0 ? age : '';
});

courseSelect.addEventListener('change', function () {
    const map = { BSIT:'CIT', BSCS:'CCS', BSECE:'COE', BSED:'COED' };
    departmentSelect.value = map[this.value] || '';
});
</script>

</body>
</html>
