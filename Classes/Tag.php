<?php
class Tag {
    private $pdo;
    
    public function __construct() {
        $this->pdo = DatabaseConnection::getInstance()->getConnection();
    }
    
    public function addTag($nom) {
        try {
            $stmt = $this->pdo->prepare("INSERT IGNORE INTO Tags (nom) VALUES (?)");
            $stmt->execute([$nom]);
            return $this->pdo->lastInsertId() ?: $this->getTagIdByName($nom);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'ajout du tag : " . $e->getMessage());
        }
    }
    
    public function getTagIdByName($nom) {
        $stmt = $this->pdo->prepare("SELECT id_tag FROM Tags WHERE nom = ?");
        $stmt->execute([$nom]);
        return $stmt->fetchColumn();
    }
    
    public function linkTagsToArticle($articleId, $tagIds) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO Articles_Tags (id_article, id_tag) VALUES (?, ?)");
            foreach ($tagIds as $tagId) {
                $stmt->execute([$articleId, $tagId]);
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la liaison des tags : " . $e->getMessage());
        }
    }
}