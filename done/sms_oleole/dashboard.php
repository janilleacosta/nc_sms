<?php
declare(strict_types=1);
session_start();
require 'dbconn.php';

$stmt = $conn->query("SELECT system_name FROM system_settings LIMIT 1");
$sys = $stmt->fetch(PDO::FETCH_ASSOC);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$isAdmin = ($_SESSION['role'] ?? '') === 'admin';
$userId  = (int)($_SESSION['user_id'] ?? 0);

$totalStudents = $itStudents = $scStudents = $eeStudents = $edStudents = $newToday = 0;
$recentStudents = [];
$filterCourse = $_GET['course'] ?? '';
$page = max((int)($_GET['page'] ?? 1), 1);
$limit = 10;
$offset = ($page - 1) * $limit;

$where = [];
$params = [];
if (!$isAdmin) {
    $where[] = "user_id = ?";
    $params[] = $userId;
}
if ($filterCourse) {
    $where[] = "course = ?";
    $params[] = $filterCourse;
}
$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

// Fetch totals and course counts
try {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM students_info_tbl $whereSQL");
    $stmt->execute($params);
    $totalStudents = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
} catch (PDOException $e) {
    $totalStudents = 0;
}

$coursesList = ['BSIT', 'BSCS', 'BSECE', 'BSED'];
$courseCounts = [];
foreach ($coursesList as $courseName) {
    $courseWhere = $where ? $whereSQL . " AND course = ?" : "WHERE course = ?";
    $courseParams = $params;
    $courseParams[] = $courseName;
    try {
        $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM students_info_tbl $courseWhere");
        $stmt->execute($courseParams);
        $courseCounts[$courseName] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    } catch (PDOException $e) {
        $courseCounts[$courseName] = 0;
    }
}
$itStudents = $courseCounts['BSIT'];
$scStudents = $courseCounts['BSCS'];
$eeStudents = $courseCounts['BSECE'];
$edStudents = $courseCounts['BSED'];

// New today
try {
    $sql = "SELECT COUNT(*) AS total FROM students_info_tbl WHERE created_at >= NOW() - INTERVAL 24 HOUR";
    if (!$isAdmin) $sql .= " AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($isAdmin ? [] : [$userId]);
    $newToday = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
} catch (PDOException $e) {
    $newToday = 0;
}

// Recent Students (for Recent Activity)
try {
    $recentSQL = "SELECT id, last_name, first_name, course, department, created_at FROM students_info_tbl";
    if (!$isAdmin) {
        $recentSQL .= " WHERE user_id = ?";
        $stmt = $conn->prepare($recentSQL . " ORDER BY created_at DESC LIMIT 10");
        $stmt->execute([$userId]);
    } else {
        $stmt = $conn->prepare($recentSQL . " ORDER BY created_at DESC LIMIT 10");
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
<title><?= htmlspecialchars($sys['system_name']) ?></title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body, html { 
    margin:0; 
    padding:0; 
    height:100%; 
    font-family:Arial,sans-serif; 
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
.container-main { 
    display:flex; 
    min-height:100vh; 
    animation: fadeIn 1s ease-in-out; 
}
@keyframes fadeIn { from {opacity:0;} to {opacity:1;} 
}
.sidebar { 
    width:220px; 
    min-width:220px; 
    background-color: rgba(52,58,64,0.85); 
    color:white; 
    padding:1rem; 
    transition: transform 0.3s ease, opacity 0.3s ease; 
}
.sidebar .nav-link { 
    color:white; 
}
.sidebar .nav-link.active { 
    font-weight:bold; 
}
.sidebar-profile img { 
    border: 2px solid white; 
}
main { 
    margin-top: 0px;
    flex:1; 
    padding:1.2rem 2rem 2rem 2rem; 
    color:white; 
    overflow-x:hidden; 
}
.page-header.mb-3 { 
    margin-bottom:1rem; 
}
/* Stats Cards */
.stat-card { 
    display:flex; 
    flex-direction:column; 
    align-items:center; 
    justify-content:center; 
    border-radius:0.5rem; 
    color:black; 
}
.stat-card i {
    display:block; 
    margin-bottom:0.5rem; 
}
.thin-icon { font-size:2rem !important; }
.large-card { padding:2rem 1rem; }
.large-card .card-title { font-size:1.25rem; }
.large-card p { font-size:2.25rem; }
.small-card { padding:1rem; }
.small-card .card-title { font-size:1rem; }
.small-card p { font-size:1.5rem; 
}
/* Card colors */
.top-card-1 { background-color:#1abc9c; }
.top-card-2 { background-color:#e67e22; }
.bottom-card-1 { background-color:#9b59b6; } 
.bottom-card-2 { background-color:#f1c40f; } 
.bottom-card-3 { background-color:#e74c3c; } 
.bottom-card-4 { background-color:#3498db; }

#sidebarToggle { display:none; }
@media(max-width:768px){
    #sidebarToggle { display:block; }
    .sidebar { transform:translateX(-100%); position:fixed; height:100%; top:0; left:0; }
    .sidebar.show { transform:translateX(0); }
}
</style>
</head>
<body>

<video autoplay muted loop playsinline class="bg-video">
    <source src="videoplayback.mp4" type="video/mp4">
</video>

<div class="container-main">
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-profile text-center mb-4">
            <a href="profile.php" class="text-white text-decoration-none">
                <img src="iconnn.jpg" alt="Profile" class="rounded-circle mb-2" width="80" height="80">
                <div class="fw-bold">Admin</div>
            </a>
        </div>
        <ul class="nav flex-column">
            <li><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
            <li><a class="nav-link" href="addStudentForm.php">Add Student</a></li>
            <li><a class="nav-link" href="viewAllStudent.php">View All Student</a></li>
            <li><a class="nav-link" href="recentAdd.php">Recently Added</a></li>
        </ul>
        <hr style="border-color: rgba(255,255,255,0.3);">
        <a href="logout.php" class="btn btn-outline-light btn-sm logout-btn d-block mt-3">Logout</a>
    </nav>

    <!-- Main Content -->
    <main class="flex-fill">
        <button id="sidebarToggle" class="d-md-none mb-3">☰ Menu</button>

        <!-- Dashboard header -->
        <div class="page-header mb-3">
            <h1 class="h1 mb-0 text-dark">Dashboard</h1>
            <p class="text-dark mb-0">Overview of student records</p>
        </div>

        <!-- Top Stats -->
        <div class="row g-3 mb-4">
            <?php
            $topStats = [
                ['title'=>'Total Students','value'=>$totalStudents,'icon'=>'bi-people-fill','class'=>'top-card-1'],
                ['title'=>'New Today','value'=>$newToday,'icon'=>'bi-calendar-day','class'=>'top-card-2']
            ];
            foreach($topStats as $stat): ?>
            <div class="col-md-6">
                <div class="card stat-card shadow-sm text-center large-card py-4 <?= $stat['class'] ?>">
                    <i class="bi <?= htmlspecialchars($stat['icon']) ?> thin-icon mb-2"></i>
                    <h5 class="card-title mb-2"><?= htmlspecialchars($stat['title']) ?></h5>
                    <p class="display-5 mb-0"><?= htmlspecialchars((string)$stat['value']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Bottom Stats -->
        <div class="row g-3 mb-4">
            <?php
            $bottomStats = [
                ['title'=>'IT Students', 'value'=>$itStudents, 'icon'=>'bi-laptop-fill','class'=>'bottom-card-1'],
                ['title'=>'Computer Science', 'value'=>$scStudents, 'icon'=>'bi-cpu-fill','class'=>'bottom-card-2'],
                ['title'=>'Engineering Students', 'value'=>$eeStudents, 'icon'=>'bi-gear-fill','class'=>'bottom-card-3'],
                ['title'=>'Educ Students', 'value'=>$edStudents, 'icon'=>'bi-book-fill','class'=>'bottom-card-4']
            ];
            foreach($bottomStats as $stat): ?>
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card shadow-sm text-center small-card py-3 <?= $stat['class'] ?>">
                    <i class="bi <?= htmlspecialchars($stat['icon']) ?> thin-icon mb-2"></i>
                    <h6 class="card-title mb-1"><?= htmlspecialchars($stat['title']) ?></h6>
                    <p class="h5 mb-0"><?= htmlspecialchars((string)$stat['value']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

<!-- Charts + Recent Activity -->
<div class="row g-3 mb-4">
    <!-- Top row: 3 cards -->
    <div class="col-md-4">
        <div class="card bg-dark text-white shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Students by Course</h5>
                <canvas id="coursePieChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-dark text-white shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Students by Department</h5>
                <canvas id="departmentBarChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-dark text-white shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">New Registrations (Last 7 Days)</h5>
                <canvas id="registrationsLineChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom row: Recent Activity full width -->
    <div class="col-12">
        <div class="card bg-dark text-white shadow-sm" style="max-height:300px; overflow-y:auto;">
            <div class="card-header bg-secondary py-1">
                <h6 class="mb-0">Recent Activity</h6>
            </div>
            <div class="card-body p-2">
                <ul class="list-group list-group-flush" id="notifList">
                    <?php if($recentStudents): ?>
                        <?php foreach($recentStudents as $s): ?>
                            <li class="list-group-item bg-dark text-white py-1 d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="bi bi-person-fill me-1"></i>
                                    <?= htmlspecialchars($s['first_name'].' '.$s['last_name']) ?> → <?= htmlspecialchars($s['course']) ?>
                                </span>
                                <small class="text-muted"><?= date('M d, H:i', strtotime($s['created_at'])) ?></small>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item bg-dark text-white text-center py-2">No recent activity.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

    </main>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
const sidebar = document.getElementById('sidebar');
const toggleBtn = document.getElementById('sidebarToggle');
toggleBtn.addEventListener('click',()=>{sidebar.classList.toggle('show');});
document.querySelectorAll('.sidebar .nav-link').forEach(link=>{
    link.addEventListener('click',()=>{if(window.innerWidth<768){sidebar.classList.remove('show');}});
});

// Charts
const courseData = {
    labels: ['BSIT','BSCS','BSECE','BSED'],
    datasets: [{
        data: [<?= $itStudents ?>, <?= $scStudents ?>, <?= $eeStudents ?>, <?= $edStudents ?>],
        backgroundColor: ['#9b59b6','#f1c40f','#e74c3c','#3498db'],
        borderColor: '#121212',
        borderWidth: 2
    }]
};

const departmentData = {
    labels: ['CIT','CCS','COE','COED'],
    datasets: [{
        label: 'Students',
        data: [<?= $itStudents+$scStudents ?>, <?= $eeStudents ?>, <?= $edStudents ?>],
        backgroundColor: ['#9b59b6','#e74c3c','#3498db']
    }]
};

<?php
$dates=[];$registrations=[];
for($i=6;$i>=0;$i--){
    $date=date('Y-m-d',strtotime("-$i days"));
    $dates[]=$date;
    $stmt=$conn->prepare("SELECT COUNT(*) AS total FROM students_info_tbl WHERE DATE(created_at)=?");
    $stmt->execute([$date]);
    $registrations[]=(int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
}
?>
const registrationData={labels:<?= json_encode($dates) ?>, datasets:[{label:'New Students', data:<?= json_encode($registrations) ?>, backgroundColor:'rgba(52,152,219,0.2)', borderColor:'#3498db', fill:true, tension:0.4}]};

new Chart(document.getElementById('coursePieChart'),{type:'pie', data:courseData, options:{plugins:{legend:{labels:{color:'white'}}}}});
new Chart(document.getElementById('departmentBarChart'),{type:'bar', data:departmentData, options:{plugins:{legend:{display:false}}, scales:{x:{ticks:{color:'white'},grid:{color:'#444'}}, y:{ticks:{color:'white'},grid:{color:'#444'}}}}});
new Chart(document.getElementById('registrationsLineChart'),{type:'line', data:registrationData, options:{plugins:{legend:{labels:{color:'white'}}}, scales:{x:{ticks:{color:'white'},grid:{color:'#444'}}, y:{ticks:{color:'white'},grid:{color:'#444'}}}}});
</script>

</body>
</html>
