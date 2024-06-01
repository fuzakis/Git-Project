<?php
// Handle POST data and insert into database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'config.php'; // File konfigurasi database

    $email = $_POST['email'];
    $password = md5($_POST['password']); // Menggunakan md5 untuk enkripsi, meski disarankan menggunakan metode yang lebih aman seperti bcrypt

    // Perintah SQL untuk memasukkan data
    $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    if ($stmt->execute()) {
        header('Location: index.php'); // Arahkan kembali ke halaman login
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="form-container">
        <form action="signup.php" method="post">
            <h2>Sign Up</h2>
            <div>
                <label>Email</label>
                <input type="email" name="email" required>
                <small>Enter your email address.</small>
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password" required>
                <small>Enter your password.</small>
            </div>
            <div class="button-container">
                <input type="submit" value="Sign Up">
            </div>
            <div>
                <a href="index.php">Already have an account? Login</a>
            </div>
        </form>
    </div>
</body>

</html>