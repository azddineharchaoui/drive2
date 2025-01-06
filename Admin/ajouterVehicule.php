<?php
require_once('../Classes/Vehicule.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nbr_cars = intval($_POST['nbr_cars']) > 0? htmlentities($_POST['nbr_cars']) : 1;
        for($i=0; $i<$nbr_cars; $i++) {
            $nomModele = $_POST["nomModele$i"];
            $idCategorie = $_POST["idCategorie$i"];
            $prixJournee = $_POST["prixJournee$i"];
            $disponibilite = $_POST["disponibilite$i"];


            if (isset($_FILES["imageUrl$i"]) && $_FILES["imageUrl$i"]['error'] === UPLOAD_ERR_OK) {
                $imagePath = $_FILES["imageUrl$i"]['tmp_name'];
                $vehicule = new Vehicule(null, $nomModele, $idCategorie, $prixJournee, $disponibilite, null);
                $vehicule->set_imageUrl($imagePath);

                if ($vehicule->ajouterVehicule()) {
                    echo "Véhicule ajouté avec succès.";
                } else {
                    echo "Erreur lors de l'ajout du véhicule.";
                }
            } else {
                echo "Erreur lors de l'upload de l'image.";
            }
        }

        header("Location: dashboard.php");

    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
