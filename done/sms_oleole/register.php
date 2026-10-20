<?php
declare(strict_types=1);
session_start();
require 'dbconn.php';

$error = '';
$success = '';

$lockDuration = 30; // seconds

// Initialize session variables if not set
if (!isset($_SESSION['captcha_attempts'])) $_SESSION['captcha_attempts'] = 0;
if (!isset($_SESSION['captcha_locked_until'])) $_SESSION['captcha_locked_until'] = 0;

// Reset CAPTCHA attempts if lock expired
if ($_SESSION['captcha_locked_until'] > 0 && time() >= $_SESSION['captcha_locked_until']) {
    $_SESSION['captcha_attempts'] = 0;
    $_SESSION['captcha_locked_until'] = 0;
}

// Generate CAPTCHA if not existing
if (!isset($_SESSION['captcha_question'])) {
    $num1 = rand(1, 9);
    $num2 = rand(1, 9);
    $_SESSION['captcha_answer'] = $num1 + $num2;
    $_SESSION['captcha_question'] = "$num1 + $num2";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_SESSION['captcha_locked_until'] > time()) {
        $remaining = $_SESSION['captcha_locked_until'] - time();
        $error = "Too many incorrect attempts. Please wait $remaining seconds.";
    } else {

        $fullname = trim($_POST['fullname'] ?? '');
        $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        $captcha  = $_POST['captcha'] ?? '';

        if (empty($fullname) || !$email || empty($password)) {
            $error = "Please fill in all fields correctly.";
        }
        elseif (strlen($password) < 6) {
            $error = "Password must be at least 6 characters.";
        }
        elseif ($captcha != ($_SESSION['captcha_answer'] ?? '')) {

            $_SESSION['captcha_attempts']++;

            if ($_SESSION['captcha_attempts'] >= 3) {
                $_SESSION['captcha_locked_until'] = time() + $lockDuration;
                $error = "Too many incorrect attempts. Please wait $lockDuration seconds.";
            } else {
                $remainingAttempts = 3 - $_SESSION['captcha_attempts'];
                $error = "Incorrect CAPTCHA answer. $remainingAttempts attempt(s) remaining.";
            }

            // Generate new CAPTCHA on wrong attempt
            $num1 = rand(1, 9);
            $num2 = rand(1, 9);
            $_SESSION['captcha_answer'] = $num1 + $num2;
            $_SESSION['captcha_question'] = "$num1 + $num2";

        } else {
            try {
                $check = $conn->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
                $check->execute([':email' => $email]);

                if ($check->fetch()) {
                    $error = "Email already registered.";
                } else {

                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = $conn->prepare("
                        INSERT INTO users (fullname, email, password, role)
                        VALUES (:fullname, :email, :password, 'user')
                    ");

                    $stmt->execute([
                        ':fullname' => $fullname,
                        ':email'    => $email,
                        ':password' => $hashedPassword
                    ]);

                    $success = "Registration successful! You may now <a href='user_login.php'>login as User</a>.";

                    // Reset CAPTCHA after success
                    $_SESSION['captcha_attempts'] = 0;
                    $_SESSION['captcha_locked_until'] = 0;

                    $num1 = rand(1, 9);
                    $num2 = rand(1, 9);
                    $_SESSION['captcha_answer'] = $num1 + $num2;
                    $_SESSION['captcha_question'] = "$num1 + $num2";
                }

            } catch (PDOException $e) {
                error_log("Registration failed: " . $e->getMessage());
                $error = "Something went wrong. Please try again later.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>
<style>
body { font-family: Arial, sans-serif; margin: 0; height: 100vh; display: flex; justify-content: center; align-items: center; background: linear-gradient(135deg, #141e30, #243b55); }
.bg-video { position: fixed; right: 0; bottom: 0; min-width: 100%; min-height: 100%; object-fit: cover; z-index: -1; }
.container { background: rgba(255,255,255,0.35); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); padding: 40px; border-radius: 15px; width: 100%; max-width: 380px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.5); }
.logo { width: 90px; margin-bottom: 10px; }
h3, h5 { margin: 0; color: #111; }
h5 { margin-bottom: 15px; font-weight: normal; font-size: 14px; color: #222; }
label { display: block; margin-top: 12px; font-weight: bold; font-size: 14px; text-align: left; color: #111; }
input { width: 100%; padding: 9px; margin-top: 5px; border-radius: 6px; border: 1px solid #ddd; font-size: 14px; }
button { width: 100%; padding: 10px; margin-top: 15px; background-color: #28a745; color: white; font-size: 14px; border: none; border-radius: 6px; cursor: pointer; }
button:hover { background-color: #218838; }
.error, .success { margin-bottom: 10px; font-size: 13px; }
.error { color: red; }
.success { color: green; }
.back-link { margin-top: 12px; font-size: 13px; }
.back-link a { color: #0056b3; text-decoration: none; }
.captcha-box { font-weight: bold; margin-top: 6px; margin-bottom: 4px; background: rgba(255,255,255,0.6); padding: 6px; border-radius: 6px; text-align: center; }
</style>
</head>
<body>

<video autoplay muted loop playsinline class="bg-video">
    <source src="videoplayback.mp4" type="video/mp4">
</video>

<div class="container">

    <img src="nclogo1.png" alt="Logo" class="logo">

    <h3>Northeastern College</h3>
    <h5>Santiago City, Isabela</h5>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">

        <label>Full Name:</label>
        <input type="text" name="fullname" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required minlength="6">

        <label>Solve this:</label>
        <div class="captcha-box"><?= $_SESSION['captcha_question'] ?? 'Loading...' ?> = ?</div>
        <input type="number" name="captcha" required>

        <button type="submit">Register</button>

    </form>

    <div class="back-link">
        <!-- Redirect user to user login instead of admin -->
        <a href="user_login.php">Back to User Login</a>
    </div>

</div>

<?php if ($_SESSION['captcha_locked_until'] > time()): ?>
<script>
let remaining = <?= $_SESSION['captcha_locked_until'] - time(); ?>;
const errorBox = document.querySelector(".error");

const countdown = setInterval(() => {
    if (remaining <= 0) {
        clearInterval(countdown);
        location.reload();
    } else {
        errorBox.innerText = "Too many incorrect attempts. Please wait " + remaining + " seconds.";
        remaining--;
    }
}, 1000);
</script>
<?php endif; ?>

</body>
</html>
