<?php
session_start();

function logout() {

    $_SESSION = [];
    
    session_destroy();
    
    header("Location: ../pages/auth_form.php");
    exit();
}

logout();
?>
