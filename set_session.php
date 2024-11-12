<?php
session_start();

if (isset($_POST['token'])) {
    $_SESSION['token'] = $_POST['token'];
    echo "Token is set to: " . $_SESSION['token']; 
} else {
    echo "No token received.";
}
?>