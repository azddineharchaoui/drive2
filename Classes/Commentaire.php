<?php
    require_once('db.php');

class Commentaire {
    private $id_commentaire;
    private $contenu;
    private $created_at;
    private $id_utilisateur;
    private $id_article;

    public function __construct($id_commentaire = null, $contenu = null, $created_at = null, $id_utilisateur = null, $id_article = null) {
        $this->id_commentaire = $id_commentaire;
        $this->contenu = $contenu;
        $this->created_at = $created_at;
        $this->id_utilisateur = $id_utilisateur;
        $this->id_article = $id_article;
    }

    public function ajouterCommentaire() {
        $pdo = DatabaseConnection::getInstance()->getConnection();
        $sql = "INSERT INTO Commentaires (contenu, created_at, id_utilisateur, id_article) VALUES (:contenu, :created_at, :id_utilisateur, :id_article)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':contenu', $this->contenu);
        $stmt->bindParam(':created_at', $this->created_at);
        $stmt->bindParam(':id_utilisateur', $this->id_utilisateur);
        $stmt->bindParam(':id_article', $this->id_article);

        return $stmt->execute();
    }

    public function modifierCommentaire($id_commentaire) {
        $pdo = DatabaseConnection::getInstance()->getConnection();

        $sql = "UPDATE Commentaires SET contenu = :contenu WHERE id_commentaire = :id_commentaire";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':contenu', $this->contenu);
        $stmt->bindParam(':id_commentaire', $id_commentaire);

        return $stmt->execute();
    }

    public function supprimerCommentaire($id_commentaire) {
        $pdo = DatabaseConnection::getInstance()->getConnection();

        $sql = "DELETE FROM Commentaires WHERE id_commentaire = :id_commentaire";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':id_commentaire', $id_commentaire);

        return $stmt->execute();
    }
}
?>