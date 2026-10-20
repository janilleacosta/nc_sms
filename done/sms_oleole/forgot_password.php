<?php
session_start();
require 'dbconn.php';

$message = '';
$error = '';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid form submission.";
    } else {

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

        if (!$email) {
            $error = "Please enter a valid email.";
        } else {

            $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {

                $code = rand(100000, 999999);
                $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

                $update = $conn->prepare("UPDATE users 
                                          SET reset_token = :code,
                                              reset_expiry = :expiry
                                          WHERE email = :email");

                $update->bindParam(':code', $code);
                $update->bindParam(':expiry', $expiry);
                $update->bindParam(':email', $email);
                $update->execute();

                $_SESSION['reset_email'] = $email;

                $message = "Your reset code is: <strong>$code</strong><br>
                            (Localhost testing only)<br><br>
                            <a href='verify_code.php'>Enter Code Here</a>";

            } else {
                $error = "Email not found.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
</head>
<body>

<h2>Forgot Password</h2>

<?php if($error): ?>
<p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if($message): ?>
<p style="color:green;"><?= $message ?></p>
<?php endif; ?>

<form method="POST">
<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

<label>Email:</label>
<input type="email" name="email" required>
<br><br>

<button type="submit">Get Reset Code</button>
</form>

</body>
</html>
