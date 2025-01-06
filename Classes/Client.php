<?php 
    require_once('Utilisateur.php');
    require_once('db.php');

    class Client extends Utilisateur {
        public function __construct($nom, $prenom, $email, $password){
            parent::__construct(null, $nom, $prenom, $email, $password, 2);
        }
        public function register() {
            try {
                $pdo = DatabaseConnection::getInstance()->getConnection();
                if ($pdo === null) {
                    echo "Erreur : la connexion à la bases de données ne peut pas être établie !";
                    return false;
                }
                echo $this->password;
                if (empty($this->password)) {
                    echo "Erreur : Le mot de passe est manquant.";
                    return false;
                }
                $sql = "INSERT INTO Utilisateurs (nom, prenom, email, mot_de_passe, id_role) VALUES (:nom, :prenom, :email, :password, :id_role)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':nom', $this->nom);
                $stmt->bindParam(':prenom', $this->prenom);
                $stmt->bindParam(':email', $this->email);
                $stmt->bindParam(':password', $this->password);
                $stmt->bindParam(':id_role', $this->id_role, PDO::PARAM_INT);
    
                if ($stmt->execute()) {
                    $this->id = $pdo->lastInsertId();
                    return true;
                }
            } catch (PDOException $e) {
                echo "Erreur d'inscription: " . $e->getMessage();
                return false;
            } catch (Exception $e) {
                echo "Erreur: " . $e->getMessage();
                return false;
            }
        }
    }

?>