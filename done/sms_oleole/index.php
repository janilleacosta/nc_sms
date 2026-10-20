<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>NC STUDENT LMS</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* SAME VIDEO BACKGROUND */
.bg-video {
    position: fixed;
    right: 0;
    bottom: 0;
    min-width: 100%;
    min-height: 100%;
    object-fit: cover;
    z-index: -1;
}

/* SAME GLASS LIGHT CONTAINER */
.container {
    text-align: center;
    padding: 50px 40px;
    width: 380px;
    border-radius: 15px;
    background: rgba(255, 255, 255, 0.35);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.5);
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    animation: fadeIn 1s ease-in-out;
}

.logo {
    width: 100px;
    margin-bottom: 15px;
}

h1 {
    margin-bottom: 30px;
    color: #111;
    font-size: 1.8em;
}

/* SAME BUTTON STYLE */
.btn {
    display: block;
    margin: 15px auto;
    padding: 12px 0;
    width: 85%;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: 0.3s;
}

.btn:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(25px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
</head>

<body>

<!-- SAME BACKGROUND VIDEO -->
<video autoplay muted loop playsinline class="bg-video">
    <source src="videoplayback.mp4" type="video/mp4">
</video>

<div class="container">
    <img src="nclogo1.png" alt="NC LMS Logo" class="logo">
    <h3>Northeastern College</h3>
    <h5>Santiago City, Isabela</h5>
    
    <a href="user_login.php" class="btn">User</a>
    <a href="login.php" class="btn">Admin</a>
</div>

</body>
</html>
