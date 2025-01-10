<?php 
require_once('db.php');

class Favori {
    private $id_favori;
    private $id_user;
    private $id_article;
    
    public function __construct($id_user, $id_article, $id_favori = null) {
        $this->id_favori = $id_favori;
        $this->id_user = $id_user;
        $this->id_article = $id_article;
    }
    
    public function ajouterFavori() {
        try {
            $pdo = DatabaseConnection::getInstance()->getConnection();
            
            // Vérifier si l'article n'est pas déjà en favori
            $check = $pdo->prepare("SELECT id_favori FROM Favoris WHERE id_utilisateur = :id_user AND id_article = :id_article");
            $check->execute([
                ':id_user' => $this->id_user,
                ':id_article' => $this->id_article
            ]);
            
            if ($check->fetch()) {
                return false; // Déjà en favori
            }
            
            // Ajouter aux favoris
            $sql = "INSERT INTO Favoris (id_utilisateur, id_article) VALUES (:id_user, :id_article)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                ':id_user' => $this->id_user,
                ':id_article' => $this->id_article
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout aux favoris : " . $e->getMessage());
            return false;
        }
    }
    
    public function supprimerFavori() {
        try {
            $pdo = DatabaseConnection::getInstance()->getConnection();
            $sql = "DELETE FROM Favoris WHERE id_utilisateur = :id_user AND id_article = :id_article";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                ':id_user' => $this->id_user,
                ':id_article' => $this->id_article
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression du favori : " . $e->getMessage());
            return false;
        }
    }
    
    public static function estFavori($id_user, $id_article) {
        try {
            $pdo = DatabaseConnection::getInstance()->getConnection();
            $sql = "SELECT id_favori FROM Favoris WHERE id_utilisateur = :id_user AND id_article = :id_article";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_user' => $id_user,
                ':id_article' => $id_article
            ]);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification du favori : " . $e->getMessage());
            return false;
        }
    }
}

// Création du fichier toggle_favori.php pour gérer les requêtes AJAX
if (basename(__FILE__) == 'toggle_favori.php') {
    session_start();
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user_id']) || !isset($_POST['article_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Données manquantes']);
        exit;
    }
    
    $favori = new Favori($_SESSION['user_id'], $_POST['article_id']);
    
    if (Favori::estFavori($_SESSION['user_id'], $_POST['article_id'])) {
        $result = $favori->supprimerFavori();
        $message = 'Article retiré des favoris';
        $is_favori = false;
    } else {
        $result = $favori->ajouterFavori();
        $message = 'Article ajouté aux favoris';
        $is_favori = true;
    }
    
    echo json_encode([
        'success' => $result,
        'message' => $message,
        'is_favori' => $is_favori
    ]);
}
?>