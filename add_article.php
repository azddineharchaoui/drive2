<?php
require_once('Classes/Article.php');
require_once('Classes/Tag.php');
require_once('Classes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_article'])) {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }

    $id_theme = $_POST['theme'];
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $id_utilisateur = $_SESSION['user_id'];
    $tags = isset($_POST['tags']) ? explode(',', $_POST['tags']) : [];
    
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            header('Location: blog2.php?error=' . urlencode('Format d\'image non supporté. Utilisez JPG, PNG ou GIF.'));
            exit();
        }
        
        if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            header('Location: blog2.php?error=' . urlencode('L\'image ne doit pas dépasser 5MB.'));
            exit();
        }
        
        $image = $_FILES['image'];
    }

    try {
        // Créer l'article
        $article = new Article($id_theme, $id_utilisateur, $titre, $contenu, $image);
        $article_id = $article->ajouterArticle();

        // Gérer les tags
        if (!empty($tags)) {
            $tagManager = new Tag();
            $tagIds = [];
            foreach ($tags as $tagName) {
                if (!empty(trim($tagName))) {
                    $tagIds[] = $tagManager->addTag(trim($tagName));
                }
            }
            if (!empty($tagIds)) {
                $tagManager->linkTagsToArticle($article_id, $tagIds);
            }
        }

        header('Location: blog2.php?success=1');
        exit();
    } catch (Exception $e) {
        header('Location: blog2.php?error=' . urlencode($e->getMessage()));
        exit();
    }
}