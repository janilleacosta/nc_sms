<?php
session_start();
require 'dbconn.php';

$error = '';
$email = '';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid form submission.";
    } else {

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$email || empty($password)) {
            $error = "Please enter a valid email and password.";
        } else {

            // Check user/admin
            $stmt = $conn->prepare("SELECT id, fullname, password, role FROM users WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header("Location: dashboard.php");
                } else {
                    header("Location: user_dashboard.php");
                }
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - NC LMS</title>

<style>
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #141e30, #243b55);
}

/* Background Video */
.bg-video {
    position: fixed;
    right: 0;
    bottom: 0;
    min-width: 100%;
    min-height: 100%;
    object-fit: cover;
    z-index: -1;
}

/* Transparent Light Container */
.container {
    background: rgba(255, 255, 255, 0.35);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 60px 40px 40px 40px;
    border-radius: 15px;
    width: 20rem;
    max-width: 380px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    border: 1px solid rgba(255,255,255,0.5);
    position: relative;
}

/* Back Button Top Left */
.back-button {
    position: absolute;
    top: 15px;
    left: 15px;
    background: white;
    color: black;
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 13px;
    text-decoration: none;
    transition: 0.3s ease;
}

.back-button:hover {
    transform: scale(1.05);
    box-shadow: 0 0 15px rgba(255,75,43,0.7);
}

.logo {
    width: 90px;
    margin-bottom: 10px;
}

h3 { margin: 5px 0 0 0; color: white; }
h5 { margin: 0 0 15px 0; font-weight: normal; font-size: 14px; color: white; }
.user { margin-top: 2rem; color: green ; }

label {
    margin-left: 1rem;
    display: block;
    margin-top: 12px;
    font-weight: bold;
    font-size: 14px;
    text-align: left;
    color: white;
}

input {
    width: 85%;
    padding: 9px;
    margin-top: 5px;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 14px;
}

button {
    width: 80%;
    padding: 11px;
    margin-top: 15px;
    background: green;
    color: white;
    font-size: 14px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s ease;
}

button:hover {
    transform: scale(1.05);
    box-shadow: 0 0 20px;
}

.error {
    margin-bottom: 10px;
    color: red;
    font-size: 13px;
}

.register-link {
    margin-top: 20px;
    font-size: 13px;
}

.register-link a {
    color: #0072ff;
    text-decoration: none;
}

.register-link a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<video autoplay muted loop playsinline class="bg-video">
    <source src="videoplayback.mp4" type="video/mp4">
</video>

<div class="container">

    <a href="index.php" class="back-button">Back</a>

    <img src="nclogo1.png" alt="Logo" class="logo">

    <h3>Northeastern College</h3>
    <h5>Santiago City, Isabela</h5>
    <h3 class="user">User Login</h3>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>

        <!-- ADDED FORGOT PASSWORD LINK (ONLY ADDITION) -->
        <div style="margin-top:10px;">
            <a href="forgot_password.php" style="color:white; font-size:13px; text-decoration:none;">
                Forgot Password?
            </a>
        </div>
    </form>

    <div class="register-link">
        <p><a href="register.php">Register here</a></p>
    </div>

</div>
</body>
</html>
