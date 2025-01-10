<?php
require_once('db.php');

class Tag {
    private $pdo;

    public function __construct() {
        $this->pdo = DatabaseConnection::getInstance()->getConnection();
    }

    public function addTag($nom) {
        // Vérifier si le tag existe déjà
        $stmt = $this->pdo->prepare("SELECT id_tag FROM Tags WHERE LOWER(nom) = LOWER(:nom)");
        $stmt->execute(['nom' => $nom]);
        $existingTag = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingTag) {
            return $existingTag['id_tag'];
        }

        // Si le tag n'existe pas, on le crée
        $stmt = $this->pdo->prepare("INSERT INTO Tags (nom) VALUES (:nom)");
        $stmt->execute(['nom' => $nom]);
        return $this->pdo->lastInsertId();
    }

    public function linkTagsToArticle($articleId, $tagIds) {
        $stmt = $this->pdo->prepare("INSERT INTO Article_Tag (id_article, id_tag) VALUES (:article_id, :tag_id)");
        foreach ($tagIds as $tagId) {
            $stmt->execute([
                'article_id' => $articleId,
                'tag_id' => $tagId
            ]);
        }
    }

    public function getTagsByArticle($articleId) {
        $stmt = $this->pdo->prepare("
            SELECT t.* 
            FROM Tags t
            JOIN Article_Tag at ON t.id_tag = at.id_tag
            WHERE at.id_article = :article_id
        ");
        $stmt->execute(['article_id' => $articleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>