<?php

require("./Classes/Client.php");


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $user = new Client($nom, $prenom, $email, $password);

    if ($user->register()) {
        session_start();
        $_SESSION['user_id'] = $user->get_id();  
        $_SESSION['role_id'] = $user->get_id_role();  
        $_SESSION['user_name'] = $user->get_nom(); 

        if ($_SESSION['role_id'] == 1) {
            header("Location: ./Admin/dashboard.php");
        } else if ($_SESSION['role_id'] == 2){
            header("Location: ./location.php");
        } else{
            header("Location: ./index.php");
        }
    } else {
        echo "Échec de l'enregistrement du client.";
    }
}

?>