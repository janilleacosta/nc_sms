<?php
require 'dbconn.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: viewAllStudent.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("DELETE FROM students_info_tbl WHERE id = :id");
$stmt->execute([':id' => $id]);

header("Location: viewAllStudent.php");
exit;
