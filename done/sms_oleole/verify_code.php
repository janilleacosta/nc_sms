<?php
session_start();
require 'dbconn.php';

$error = '';
$email = $_SESSION['reset_email'] ?? '';

if (!$email) {
    die("Session expired.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $code = $_POST['code'] ?? '';

    $stmt = $conn->prepare("SELECT id, reset_expiry FROM users 
                            WHERE email = :email 
                            AND reset_token = :code 
                            LIMIT 1");

    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':code', $code);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        if (strtotime($user['reset_expiry']) < time()) {
            $error = "Code expired.";
        } else {
            $_SESSION['verified_user'] = $user['id'];
            header("Location: reset_password.php");
            exit;
        }

    } else {
        $error = "Invalid code.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Verify Code</title>
</head>
<body>

<h2>Enter Reset Code</h2>

<?php if($error): ?>
<p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST">
<label>Enter 6-digit Code:</label>
<input type="text" name="code" required>
<br><br>

<button type="submit">Verify</button>
</form>

</body>
</html>
