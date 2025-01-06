<?php
require_once('db.php');

class Vehicule {
    private $id;
    private $nomModele;
    private $idCategorie;
    private $prixJournee;
    private $disponibilite;
    private $imageUrl;

    public function __construct($id = null, $nomModele = null, $idCategorie = null, $prixJournee = null, $disponibilite = null, $imageUrl = null) {
        $this->id = $id;
        $this->nomModele = $nomModele;
        $this->idCategorie = $idCategorie;
        $this->prixJournee = $prixJournee;
        $this->disponibilite = $disponibilite;
        $this->imageUrl = $imageUrl;
    }

    public function set_imageUrl($imagePath) {
        if (file_exists($imagePath)) {
            $this->imageUrl = file_get_contents($imagePath); 
        } else {
            throw new Exception("Le fichier image spécifié est introuvable.");
        }
    }

    public function ajouterVehicule() {
        $pdo = DatabaseConnection::getInstance()->getConnection();
        $query = "INSERT INTO Vehicules (nom_modele, id_categorie, prix_journee, disponibilite, image_url) 
                  VALUES (:nomModele, :idCategorie, :prixJournee, :disponibilite, :imageUrl)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':nomModele', $this->nomModele);
        $stmt->bindParam(':idCategorie', $this->idCategorie, PDO::PARAM_INT);
        $stmt->bindParam(':prixJournee', $this->prixJournee);
        $stmt->bindParam(':disponibilite', $this->disponibilite);
        $stmt->bindParam(':imageUrl', $this->imageUrl, PDO::PARAM_LOB); 
        return $stmt->execute();
    }

    public static function afficherDetails($id) {
        $pdo = DatabaseConnection::getInstance()->getConnection();
        $query = "SELECT * FROM Vehicules WHERE id_vehicule = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function afficherImage($id) {
        $pdo = DatabaseConnection::getInstance()->getConnection();
        $query = "SELECT image_url FROM Vehicules WHERE id_vehicule = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            header("Content-Type: image/jpeg");
            echo $result['image_url'];
            exit;
        } else {
            throw new Exception("Aucune image trouvée pour le véhicule ID: $id.");
        }
    }

    
    public function modifierVehicule($id) {
        $pdo = DatabaseConnection::getInstance()->getConnection();
        $query = "UPDATE Vehicules 
                  SET nom_modele = :nomModele, id_categorie = :idCategorie, prix_journee = :prixJournee, disponibilite = :disponibilite, image_url = :imageUrl 
                  WHERE id_vehicule = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':nomModele', $this->nomModele);
        $stmt->bindParam(':idCategorie', $this->idCategorie);
        $stmt->bindParam(':prixJournee', $this->prixJournee);
        $stmt->bindParam(':disponibilite', $this->disponibilite);
        $stmt->bindParam(':imageUrl', $this->imageUrl, PDO::PARAM_LOB);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function supprimerVehicule($id) {
        $pdo = DatabaseConnection::getInstance()->getConnection();
        $query = "DELETE FROM Vehicules WHERE id_vehicule = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public static function listeVehicules() {
        $pdo = DatabaseConnection::getInstance()->getConnection();
        if (!$pdo) {
            echo "Erreur de connexion à la base de données.";
            return [];
        }
        $query = "SELECT * FROM Vehicules as v inner join Categories as c on v.id_categorie = c.id_categorie";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>
