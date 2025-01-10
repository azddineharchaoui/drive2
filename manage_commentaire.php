<?php
session_start();
require_once('Classes/db.php');
require_once('Classes/Commentaire.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

function isCommentOwner($commentId, $userId) {
    try {
        $pdo = DatabaseConnection::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT id_utilisateur FROM Commentaires WHERE id_commentaire = :id_commentaire");
        $stmt->execute(['id_commentaire' => $commentId]);
        $comment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $comment && $comment['id_utilisateur'] == $userId;
    } catch (Exception $e) {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id_article = $_POST['id_article'] ?? null;
    
    try {
        switch ($action) {
            case 'create':
                if (empty($_POST['contenu'])) {
                    throw new Exception("Le contenu du commentaire ne peut pas être vide.");
                }

                $commentaire = new Commentaire(
                    null,
                    $_POST['contenu'],
                    date('Y-m-d H:i:s'),
                    $_SESSION['user_id'],
                    $id_article
                );

                if ($commentaire->ajouterCommentaire()) {
                    $_SESSION['success'] = "Commentaire ajouté avec succès.";
                } else {
                    throw new Exception("Erreur lors de l'ajout du commentaire.");
                }
                break;

            case 'update':
                $id_commentaire = $_POST['id_commentaire'] ?? null;
                
                if (!$id_commentaire || !isCommentOwner($id_commentaire, $_SESSION['user_id'])) {
                    throw new Exception("Vous n'êtes pas autorisé à modifier ce commentaire.");
                }

                if (empty($_POST['contenu'])) {
                    throw new Exception("Le contenu du commentaire ne peut pas être vide.");
                }

                $commentaire = new Commentaire(
                    $id_commentaire,
                    $_POST['contenu']
                );

                if ($commentaire->modifierCommentaire($id_commentaire)) {
                    $_SESSION['success'] = "Commentaire modifié avec succès.";
                } else {
                    throw new Exception("Erreur lors de la modification du commentaire.");
                }
                break;

            case 'delete':
                $id_commentaire = $_POST['id_commentaire'] ?? null;
                
                if (!$id_commentaire || !isCommentOwner($id_commentaire, $_SESSION['user_id'])) {
                    throw new Exception("Vous n'êtes pas autorisé à supprimer ce commentaire.");
                }

                $commentaire = new Commentaire();
                if ($commentaire->supprimerCommentaire($id_commentaire)) {
                    $_SESSION['success'] = "Commentaire supprimé avec succès.";
                } else {
                    throw new Exception("Erreur lors de la suppression du commentaire.");
                }
                break;

            default:
                throw new Exception("Action non valide.");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Erreur : " . $e->getMessage();
    }

    if ($id_article) {
        header('Location: article.php?id=' . $id_article . '#commentaires');
    } else {
        header('Location: blog2.php');
    }
    exit();
}

header('Location: blog2.php');
exit();
?>