<?php 
    require_once('../Classes/Avis.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['delete_avis'])) {
            $id = intval($_POST['delete_avis']);
            if (Avis::supprimerAvis($id)) {
                header("Location: ./dashboard.php?success=1");
            } else {
                header("Location: ./dashboard.php?error=1");
            }
            exit();
        }
    
        if (isset($_POST['edit_avis'])) {
            $id = intval($_POST['id_avis']);
            $commentaire = trim($_POST['commentaire']);
            $evaluation = intval($_POST['evaluation']);
    
            $avis = new Avis($id);
            $avis->setCommentaire($commentaire);
            $avis->setEvaluation($evaluation);
    
            if ($avis->modifierAvis($id)) {
                header("Location: ./dashboard.php?success=1");
            } else {
                header("Location: ./dashboard.php?error=1");
            }
            exit();
        }
    }
    
?>