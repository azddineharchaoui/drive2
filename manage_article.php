<?php
require_once('./Classes/Article.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $article = new Article(
                    $_POST['id_theme'],
                    $_SESSION['user_id'],
                    $_POST['titre'],
                    $_POST['contenu']
                );
                $article->ajouterArticle();
                break;
                
            case 'update':
                $article = new Article(
                    $_POST['id_theme'],
                    $_SESSION['user_id'],
                    $_POST['titre'],
                    $_POST['contenu']
                );
                $article->modifierArticle($_POST['id_article']);
                break;
                
            case 'delete':
                Article::supprimerArticle($_POST['id_article']);
                break;
        }
    }
    
    header('Location: dashboard.php#articles');
    exit();
}