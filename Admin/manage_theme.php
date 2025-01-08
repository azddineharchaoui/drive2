<?php
require_once("../Classes/db.php"); 
require_once("../Classes/Theme.php");

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo = DatabaseConnection::getInstance()->getConnection();

    $stmt = $pdo->prepare("DELETE FROM Themes WHERE id_theme = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_theme'])) {
    $id = $_POST['id_theme'];
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $pdo = DatabaseConnection::getInstance()->getConnection();

    $stmt = $pdo->prepare("UPDATE Themes SET nom = :nom, description = :description WHERE id_theme = :id");
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_theme'])) {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $pdo = DatabaseConnection::getInstance()->getConnection();

    $stmt = $pdo->prepare("INSERT INTO Themes (nom, description) VALUES (:nom, :description)");
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    header("Location: dashboard.php");
    exit;
}


?>

