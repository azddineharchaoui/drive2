<?php
include_once('./Classes/Utilisateur.php');
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    error_log("Logout triggered");
    Utilisateur::logout();
}

?>