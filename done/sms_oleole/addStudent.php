<?php 
require 'dbconn.php';
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $last_name      = $_POST['last_name'];
    $first_name     = $_POST['first_name'];
    $middle_initial = $_POST['middle_initial'];
    $extension_name = $_POST['extension_name'];
    $age            = $_POST['age'];
    $birthdate      = $_POST['birthdate'];

    $street         = $_POST['street'];
    $barangay       = $_POST['barangay'];
    $city           = $_POST['city'];
    $province       = $_POST['province'];
    $zip_code       = $_POST['zip_code'];

    $course         = $_POST['course'];
    $department     = $_POST['department'];

    $sql = "INSERT INTO students_info_tbl
            (last_name, first_name, middle_initial, extension_name, age, birthdate, street, barangay, city, province, zip_code, course, department, user_id)
            VALUES
            (:last_name, :first_name, :middle_initial, :extension_name, :age, :birthdate, :street, :barangay, :city, :province, :zip_code, :course, :department, :user_id)";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':last_name' => $last_name,
        ':first_name'=> $first_name,
        ':middle_initial'=> $middle_initial,
        ':extension_name'=> $extension_name,
        ':age'=> $age,
        ':birthdate'=> $birthdate,
        ':street'=> $street,
        ':barangay'=> $barangay,
        ':city'=> $city,
        ':province'=> $province,
        ':zip_code'=> $zip_code,
        ':course'=> $course,
        ':department'=> $department,
        ':user_id'=> $userId
    ]);

    header("Location: index.php");
    exit(); 
}else{
    echo 'Invalid request method.';
}
?>
