<?php 
    require_once("db.php");

    class Tag{
        private $id_tag;
        private $nom;
        public function __construct($id_tag, $nom){
            $this->id_tag = $id_tag;
            $this->nom = $nom;
        }
        public function ajouterTag() {
            $pdo = DatabaseConnection::getInstance()->getConnection();
            try {
                $stmt = $pdo->prepare("INSERT INTO Tags (nom) VALUES (:nom)");
                $stmt->bindParam(':nom', $this->nom);
                $stmt->execute();
                return $pdo->lastInsertId();
            } catch (PDOException $e) {
                die("Erreur lors de l'ajout du tag : " . $e->getMessage());
            }
        }
    
        public function modifierTag() {
            $pdo = DatabaseConnection::getInstance()->getConnection();
            try {
                $stmt = $pdo->prepare("UPDATE Tags SET nom = :nom WHERE id_tag = :id_tag");
                $stmt->bindParam(':nom', $this->nom);
                $stmt->bindParam(':id_tag', $this->id_tag);
                return $stmt->execute();
            } catch (PDOException $e) {
                die("Erreur lors de la modification du tag : " . $e->getMessage());
            }
        }
    
        public function supprimerTag() {
            $pdo = DatabaseConnection::getInstance()->getConnection();
            try {
                $stmt = $pdo->prepare("DELETE FROM Tags WHERE id_tag = :id_tag");
                $stmt->bindParam(':id_tag', $this->id_tag);
                return $stmt->execute();
            } catch (PDOException $e) {
                die("Erreur lors de la suppression du tag : " . $e->getMessage());
            }
        }
    
        public static function recupererTousTags() {
            $pdo = DatabaseConnection::getInstance()->getConnection();
            try {
                $stmt = $pdo->query("SELECT * FROM Tags");
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Erreur lors de la récupération des tags : " . $e->getMessage());
            }
        }
    }
?>