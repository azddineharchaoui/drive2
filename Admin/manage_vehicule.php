<?php 
    require_once("../Classes/Vehicule.php");
    require_once("../Classes/db.php");

    error_log("Debug POST: " . print_r($_POST, true));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_vehicule'])) {
    $id_v = $_POST['delete_vehicule'];
    if(Vehicule::supprimerVehicule($id_v)){
        header("Location: ./dashboard.php");
    } else {
        header("Location: ./dashboard.php");
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_vehicule'])) {
    error_log("Debug POST: " . print_r($_POST, true));

    $id = isset($_POST['id_vehicule']) ? intval($_POST['id_vehicule']) : null;
    $nomModele = isset($_POST['nom_modele']) ? trim($_POST['nom_modele']) : '';
    $idCategorie = isset($_POST['id_categorie']) ? intval($_POST['id_categorie']) : null;
    $prixJournee = isset($_POST['prix_journee']) ? floatval($_POST['prix_journee']) : null;
    $disponibilite = isset($_POST['disponibilite']) ? intval($_POST['disponibilite']) : null;


    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
        $imagePath = $_FILES['image_url']['tmp_name'];
        $imageContent = file_get_contents($imagePath);
    } else {
        $vehicule = Vehicule::afficherDetails($id);
        $imageContent = $vehicule['image_url'] ?? null;
    }

    
    $vehicule = new Vehicule($id, $nomModele, $idCategorie, $prixJournee, $disponibilite, $imageContent);
    if ($vehicule->modifierVehicule($id)) {
        header("Location: ./dashboard.php?success=1");
    } else {
        header("Location: ./dashboard.php?error=1");
    }
}


?>