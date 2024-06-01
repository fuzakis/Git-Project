<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
} else {
    header('Location: ../Audio_editor/index.php'); // Redirect ke index.php dalam folder Audio_editor
    exit;
}
?>
