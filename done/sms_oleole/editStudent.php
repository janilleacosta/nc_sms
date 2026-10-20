<?php 
require 'dbconn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Basic validation and sanitization
    $student_id = isset($_POST['student_id']) ? (int)$_POST['student_id'] : 0;
    if ($student_id <= 0) {
        die('Invalid student ID.');
    }

    $last_name = trim($_POST['last_name'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $middle_initial = trim($_POST['middle_initial'] ?? '');
    $extension_name = trim($_POST['extension_name'] ?? '');
    $age = isset($_POST['age']) ? (int)$_POST['age'] : 0;
    $birthdate = trim($_POST['birthdate'] ?? '');
    $street = trim($_POST['street'] ?? '');
    $barangay = trim($_POST['barangay'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $province = trim($_POST['province'] ?? '');
    $zip_code = trim($_POST['zip_code'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $department = trim($_POST['department'] ?? '');

    // Ensure required fields are not empty
    if (empty($last_name) || empty($first_name) || empty($age) || empty($birthdate) || empty($street) || empty($barangay) || empty($city) || empty($province) || empty($zip_code) || empty($course) || empty($department)) {
        die('All required fields must be filled.');
    }

    // FIXED: changed WHERE student_id to WHERE id
    $sql = "UPDATE students_info_tbl SET
        last_name = :last_name,
        first_name = :first_name,
        middle_initial = :middle_initial,
        extension_name = :extension_name,
        age = :age,
        birthdate = :birthdate,
        street = :street,
        barangay = :barangay,
        city = :city,
        province = :province,
        zip_code = :zip_code,
        course = :course,
        department = :department
        WHERE id = :student_id";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':last_name' => $last_name,
            ':first_name' => $first_name,
            ':middle_initial' => $middle_initial,
            ':extension_name' => $extension_name,
            ':age' => $age,
            ':birthdate' => $birthdate,
            ':street' => $street,
            ':barangay' => $barangay,
            ':city' => $city,
            ':province' => $province,
            ':zip_code' => $zip_code,
            ':course' => $course,
            ':department' => $department,
            ':student_id' => $student_id, // FIXED (was $id)
        ]);

        header("Location: dashboard.php?success=1"); 
        exit();

    } catch (PDOException $e) {
        die('Update failed: ' . $e->getMessage());
    }

} else {
    echo 'Invalid request method.';
}
?>
