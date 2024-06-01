<?php
session_start();
session_unset();
session_destroy();
header('Location: index.php'); // Ubah ini ke halaman login kamu jika berbeda
exit;
?>
