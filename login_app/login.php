<?php
session_start();

$db = new PDO('mysql:host=localhost;dbname=login_system;charset=utf8', 'root', ''); // Sesuaikan dengan username dan password Anda

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $statement = $db->prepare("SELECT * FROM users WHERE email = ?");
    $statement->execute([$email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['password'] === md5($password)) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: dashboard.php');
        exit;
    } else {
        echo "<script>alert('Invalid email or password!'); window.location.href = 'index.php';</script>";
    }
    
}
?>
