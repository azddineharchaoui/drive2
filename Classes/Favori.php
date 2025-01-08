<?php 
    require_once('db.php');
    class Favori {
        private $id_favori;
        private $id_user;
        private $id_article;
        public function __construct($id_favori, $id_user, $id_article){
            $this->id_favori = $id_favori;
            $this->id_user = $id_user;
            $this->id_article = $id_article;
        }
        public function ajouterFavori(){

        }
        public function supprimerFavori(){
            
        }
    }
?>