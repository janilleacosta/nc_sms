<?php
session_start();
require 'dbconn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Fetch current settings
$stmt = $conn->query("SELECT * FROM system_settings LIMIT 1");
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $system_name = $_POST['system_name'];
    $registration_enabled = isset($_POST['registration_enabled']) ? 1 : 0;

    // Handle logo upload
    if (!empty($_FILES['logo']['name'])) {
        $logoName = time() . "_" . $_FILES['logo']['name'];
        move_uploaded_file($_FILES['logo']['tmp_name'], "uploads/" . $logoName);
    } else {
        $logoName = $settings['logo'];
    }

    $update = $conn->prepare("UPDATE system_settings SET system_name=?, logo=?, registration_enabled=? WHERE id=?");
    $update->execute([$system_name, $logoName, $registration_enabled, $settings['id']]);

    header("Location: settings.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>System Settings</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-dark text-white">

<div class="container mt-5">
    <div class="card bg-dark border-light shadow">
        <div class="card-header">
            <h4>⚙ System Settings</h4>
        </div>
        <div class="card-body">

            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success">Settings Updated Successfully!</div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label>System Name</label>
                    <input type="text" name="system_name" class="form-control"
                           value="<?= htmlspecialchars($settings['system_name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label>Current Logo</label><br>
                    <img src="uploads/<?= htmlspecialchars($settings['logo']) ?>" width="120">
                </div>

                <div class="mb-3">
                    <label>Change Logo</label>
                    <input type="file" name="logo" class="form-control">
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox"
                           name="registration_enabled"
                           <?= $settings['registration_enabled'] ? 'checked' : '' ?>>
                    <label class="form-check-label">
                        Enable Student Registration
                    </label>
                </div>

                <button class="btn btn-primary">Save Settings</button>
                <a href="index.php" class="btn btn-secondary">Back</a>

            </form>

        </div>
    </div>
</div>

</body>
</html>
