<?php
include_once('./Classes/Utilisateur.php');
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    error_log("Logout triggered");
    Utilisateur::logout();
    header("Location: index.php");
}

?>