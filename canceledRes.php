<?php
session_start();
require_once 'Classes/Reservation.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_res'])) {
    try {
        $reservation = new Reservation();
        $id_reservation = $_POST['id_res'];
        
        if ($reservation->annulerReservation($id_reservation)) {
            header("Location: location.php?message=cancel_success");
        } else {
            header("Location: location.php?error=cancel_failed");
        }
    } catch (Exception $e) {
        header("Location: location.php?error=system_error");
    }
    exit();
} else {
    header("Location: location.php");
    exit();
}
?>