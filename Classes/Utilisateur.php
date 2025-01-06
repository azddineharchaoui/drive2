<?php 
    require_once('db.php');

    abstract class Utilisateur {
        protected $id; 
        protected $nom; 
        protected $prenom;
        protected $email;
        protected $password;
        protected $id_role;

        public function __construct($id, $nom, $prenom, $email, $password, $id_role){
            $this->id = $id; 
            $this->nom = $nom; 
            $this->prenom = $prenom; 
            $this->email = $email;
            $this->password = $password;
            $this->id_role = $id_role; 
        }
        public function get_id(){
            return $this->id;
        }
        public function set_id($id){
            $this->id = $id;
        }
        public function get_nom(){
            return $this->nom;
        }
        public function set_nom($nom){
            $this->nom = $nom;
        }
        public function get_prenom(){
            return $this->prenom;
        }
        public function set_prenom($prenom){
            $this->prenom = $prenom;
        }
        public function get_email(){
            return $this->email;
        }
        public function set_email($email){
            $this->email = $email;
        }
        public function get_password(){
            return $this->password;
        }
        public function set_password($password){
            $this->password = password_hash($password, PASSWORD_BCRYPT);
        }
        public function get_id_role(){
            return $this->id_role;
        }
        public function set_id_role($id_role){
            $this->id_role = $id_role;
        }
        
        public static function login($email, $password) {
            $pdo = DatabaseConnection::getInstance()->getConnection();
            if (!$pdo) {
                echo "Erreur de connexion à la base de données.";
                return null;
            }
            
            $query = "SELECT u.id_utilisateur, u.nom, u.mot_de_passe, r.id_role 
                      FROM Utilisateurs u 
                      INNER JOIN roles r ON u.id_role = r.id_role 
                      WHERE u.email = :email";
        
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
        
            if ($stmt->rowCount() === 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
                if (password_verify($password, $user['mot_de_passe'])) {
                    session_start();
                    $_SESSION['user_id'] = $user['id_utilisateur'];
                    $_SESSION['role_id'] = $user['id_role'];
                    $_SESSION['user_name'] = $user['nom'];
        
                    if ($_SESSION['role_id'] == 1) {
                        header("Location: ./Admin/dashboard.php");
                    } else {
                        header("Location: ./location.php");
                    }
                } else {
                    echo "<script>alert('Mot de passe incorrect. Veuillez réessayer.');</script>";
                    header("Refresh: 0; URL=index.php");
                }
            } else {
                echo "<script>alert('Adresse e-mail introuvable. Veuillez vérifier vos informations.');</script>";
                header("Refresh: 0; URL=index.php");
            }
        }
        
        public static function logout() {
            session_start();
        
            if (isset($_SESSION['user_id'])) {
                session_unset();
                session_destroy();
                header("Location: ./index.php");  
                exit();
            }
        }

    }

?>