<?php
session_start();
require 'dbconn.php';

$error = '';
$message = '';
$user_id = $_SESSION['verified_user'] ?? '';

if (!$user_id) {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $update = $conn->prepare("UPDATE users 
                                  SET password = :password,
                                      reset_token = NULL,
                                      reset_expiry = NULL
                                  WHERE id = :id");

        $update->bindParam(':password', $hashed);
        $update->bindParam(':id', $user_id);
        $update->execute();

        unset($_SESSION['verified_user']);
        unset($_SESSION['reset_email']);

        $message = "Password successfully reset. <a href='login.php'>Login here</a>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Reset Password</title>
</head>
<body>

<h2>Reset Password</h2>

<?php if($error): ?>
<p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if($message): ?>
<p style="color:green;"><?= $message ?></p>
<?php endif; ?>

<?php if(!$message): ?>
<form method="POST">
<label>New Password:</label>
<input type="password" name="password" required>
<br><br>

<label>Confirm Password:</label>
<input type="password" name="confirm_password" required>
<br><br>

<button type="submit">Reset Password</button>
</form>
<?php endif; ?>

</body>
</html>
