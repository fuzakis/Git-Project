<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script src="https://kit.fontawesome.com/a88ce4f8a0.js" crossorigin="anonymous"></script>
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle input[type="password"] {
            padding-right: 10px;
        }

        .password-toggle .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            user-select: none;
            /* Prevent selection */
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .welcome-message {
            text-align: center;
            margin-bottom: 20px;
            margin-top: -50px;
            /* Menggeser tulisan "Selamat Datang" ke atas */
            position: relative;
            /* Menjadikan posisi relatif */
            z-index: 1;
            /* Menjadikan z-index lebih tinggi dari elemen lain */
            margin-left: -20px;
            margin-right: 200px;
            /* Menggeser teks ke kiri */
        }


        .welcome-message h1 {
            color: #007BFF;
        }
    </style>
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