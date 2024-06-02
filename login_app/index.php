<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script src="https://kit.fontawesome.com/a88ce4f8a0.js" crossorigin="anonymous"></script>
    <title>Login Page</title>
    <link rel="stylesheet" href="styles2.css">
</head>

<body>
    <div class="welcome-message">
        <h1>Selamat Datang</h1>
    </div>
    <form action="login.php" method="post">
        <h2>Login</h2>
        <div>
            <label>Email</label>
            <input type="email" name="email" required>
            <small>Enter your registered email address.</small>
        </div>
        <div class="password-toggle">
            <label>Password</label>
            <input type="password" name="password" id="passwordInput" required>
            <span class="toggle-password" onclick="togglePasswordVisibility()">
                <i class="fa-regular fa-eye"></i></span>
            <small>Enter your password.</small>
        </div>
        <div>
            <input type="submit" value="Login">
        </div>
        <div>
            <a href="signup.php" style="color: #007BFF; text-decoration: none;">Sign up</a>
        </div>
    </form>
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('passwordInput');
            const toggleText = document.querySelector('.toggle-password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleText.innerHTML = '<i class="fa-regular fa-eye-slash"></i>';
            } else {
                passwordInput.type = 'password';
                toggleText.innerHTML = '<i class="fa-regular fa-eye"></i>';
            }
        }
    </script>
</body>

</html>