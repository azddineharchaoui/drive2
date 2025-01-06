<?php
session_start();
require_once 'Classes/Reservation.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_utilisateur = $_SESSION['user_id'];
        $id_vehicule = $_POST['id_voiture'];
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
        $lieu_depart = $_POST['lieu_depart'];
        $lieu_retour = $_POST['lieu_retour'];

        $reservation = new Reservation(
            null,
            $id_utilisateur,
            $id_vehicule,
            $date_debut,
            $date_fin,
            $lieu_depart,
            $lieu_retour
        );

        if ($reservation->ajouterReservation()) {
            header("Location: location.php?message=success");
        } else {
            header("Location: location.php?error=vehicule_indisponible");
        }
    } catch (Exception $e) {
        header("Location: location.php?error=reservation_failed");
    }
    exit();
}
?>