<?php
require_once('db.php');

class Article {
    private $id;
    private $id_theme;
    private $id_utilisateur;
    private $titre;
    private $contenu;
    private $statut;
    private $image; // Nouvel attribut

    public function __construct($id_theme, $id_utilisateur, $titre, $contenu, $image = null, $statut = 'En attente') {
        $this->id_theme = $id_theme;
        $this->id_utilisateur = $id_utilisateur;
        $this->titre = $titre;
        $this->contenu = $contenu;
        $this->image = $image;
        $this->statut = $statut;
    }

    public function ajouterArticle() {
        $pdo = DatabaseConnection::getInstance()->getConnection();
    
        $image_path = null;
        if ($this->image && $this->image['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/articles/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
    
            $extension = pathinfo($this->image['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            $image_path = $upload_dir . $filename;
    
            if (!move_uploaded_file($this->image['tmp_name'], $image_path)) {
                throw new Exception("Erreur lors de l'upload de l'image");
            }
        }
    
        $sql = "INSERT INTO Articles (id_theme, id_utilisateur, titre, contenu, image_url, statut) 
                VALUES (:id_theme, :id_utilisateur, :titre, :contenu, :image_url, :statut)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_theme' => $this->id_theme,
            ':id_utilisateur' => $this->id_utilisateur,
            ':titre' => $this->titre,
            ':contenu' => $this->contenu,
            ':image_url' => $image_path,
            ':statut' => $this->statut,
        ]);
        
        return $pdo->lastInsertId(); 
    }
    public function modifierArticle($id) {
        $pdo = DatabaseConnection::getInstance()->getConnection();

        $stmt = $pdo->prepare("SELECT image_url FROM Articles WHERE id_article = :id");
        $stmt->execute([':id' => $id]);
        $old_image = $stmt->fetchColumn();

        $image_path = $old_image;
        if ($this->image && $this->image['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/articles/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $extension = pathinfo($this->image['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            $image_path = $upload_dir . $filename;

            if (move_uploaded_file($this->image['tmp_name'], $image_path)) {
                if ($old_image && file_exists($old_image)) {
                    unlink($old_image);
                }
            } else {
                throw new Exception("Erreur lors de l'upload de l'image");
            }
        }

        $sql = "UPDATE Articles 
                SET id_theme = :id_theme, titre = :titre, contenu = :contenu, image_url = :image_url, statut = :statut 
                WHERE id_article = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':id_theme' => $this->id_theme,
            ':titre' => $this->titre,
            ':contenu' => $this->contenu,
            ':image_url' => $image_path,
            ':statut' => $this->statut,
        ]);
    }

    public function supprimerArticle($id) {
        $pdo = DatabaseConnection::getInstance()->getConnection();
        
        // Récupérer l'image avant la suppression
        $stmt = $pdo->prepare("SELECT image_url FROM Articles WHERE id_article = :id");
        $stmt->execute([':id' => $id]);
        $image_url = $stmt->fetchColumn();

        // Supprimer le fichier image si il existe
        if ($image_url && file_exists($image_url)) {
            unlink($image_url);
        }

        // Supprimer l'article
        $sql = "DELETE FROM Articles WHERE id_article = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    // Les autres méthodes restent inchangées...
    public static function accepterArticle($id_article) {
        try {
            $pdo = DatabaseConnection::getInstance()->getConnection();
            $sql = "UPDATE Articles SET statut = 'Accepté' WHERE id_article = :id";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([':id' => $id_article]);
        } catch (PDOException $e) {
            error_log("Erreur lors de l'acceptation de l'article: " . $e->getMessage());
            return false;
        }
    }

    public static function refuserArticle($id_article) {
        try {
            $pdo = DatabaseConnection::getInstance()->getConnection();
            $sql = "UPDATE Articles SET statut = 'Refusé' WHERE id_article = :id";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([':id' => $id_article]);
        } catch (PDOException $e) {
            error_log("Erreur lors du refus de l'article: " . $e->getMessage());
            return false;
        }
    }

    public static function listerArticles() {
        $pdo = DatabaseConnection::getInstance()->getConnection();
        $sql = "SELECT Articles.*, Themes.nom AS theme_nom
                FROM Articles 
                LEFT JOIN Themes ON Articles.id_theme = Themes.id_theme
                ORDER BY Articles.date_creation DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>