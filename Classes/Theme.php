<?php 
    require_once("db.php");
    
    class Theme {
        private $id_theme; 
        private $nom; 
        private $description;
        public function __construct($id_theme = null, $nom, $description){
            $this->id_theme = $id_theme; 
            $this->nom = $nom;
            $this->description = $description;
        }
        public function ajouterTheme() {
            $pdo = DatabaseConnection::getInstance()->getConnection();
    
            try {
                $sql = "INSERT INTO Themes (nom, description) VALUES (:nom, :description)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':nom', $this->nom);
                $stmt->bindParam(':description', $this->description);
                $stmt->execute();
    
                $this->id_theme = $pdo->lastInsertId();
                return true; 
            } catch (PDOException $e) {
                echo "Erreur lors de l'ajout du thème : " . $e->getMessage();
                return false;
            }
        }
    }

?>