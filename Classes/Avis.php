<?php
require_once('db.php');

class Avis {
    private $id;
    private $idVehicule;
    private $idClient;
    private $commentaire;
    private $evaluation;
    private $dateCreation;

    public function __construct($id = null, $idVehicule = null, $idClient = null, $commentaire = null, $evaluation = null, $dateCreation = null) {
        $this->id = $id;
        $this->idVehicule = $idVehicule;
        $this->idClient = $idClient;
        $this->commentaire = $commentaire;
        $this->evaluation = $evaluation;
        $this->dateCreation = $dateCreation;
    }

    public function ajouterAvis() {
        $pdo = DatabaseConnection::getInstance()->getConnection();
        $query = "INSERT INTO Avis (id_vehicule, id_client, commentaire) VALUES (:idVehicule, :idClient, :commentaire)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':idVehicule', $this->idVehicule, PDO::PARAM_INT);
        $stmt->bindParam(':idClient', $this->idClient, PDO::PARAM_INT);
        $stmt->bindParam(':commentaire', $this->commentaire, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $this->id = $pdo->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    public function modifierAvis($id) {
        $pdo = DatabaseConnection::getInstance()->getConnection();
        $query = "UPDATE Avis SET commentaire = :commentaire WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':commentaire', $this->commentaire, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function supprimerAvis($id) {
        $pdo = DatabaseConnection::getInstance()->getConnection();
        $query = "DELETE FROM Avis WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function listerAvisParVehicule($idVehicule) {
        $pdo = DatabaseConnection::getInstance()->getConnection();
        $query = "SELECT * FROM Avis WHERE id_vehicule = :idVehicule";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':idVehicule', $idVehicule, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getIdVehicule() {
        return $this->idVehicule;
    }

    public function setIdVehicule($idVehicule) {
        $this->idVehicule = $idVehicule;
    }

    public function getIdClient() {
        return $this->idClient;
    }

    public function setIdClient($idClient) {
        $this->idClient = $idClient;
    }

    public function getCommentaire() {
        return $this->commentaire;
    }

    public function setCommentaire($commentaire) {
        $this->commentaire = $commentaire;
    }

    public function getDateCreation() {
        return $this->dateCreation;
    }

    public function setDateCreation($dateCreation) {
        $this->dateCreation = $dateCreation;
    }
}
?>
