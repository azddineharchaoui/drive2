<?php 
require_once('./Classes/db.php');

if(isset($_POST['input'])){
    $pdo = DatabaseConnection::getInstance()->getConnection();
    $input = $_POST['input'];
    $query = "SELECT v.*, c.nom_categorie 
              FROM Vehicules v 
              INNER JOIN Categories c ON v.id_categorie = c.id_categorie 
              WHERE v.nom_modele LIKE :input";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':input', $input . '%', PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($result) {
        foreach($result as $offer) {
            echo '
            <div class="bg-gray-50 rounded-lg shadow-lg hover:shadow-xl transition duration-300 overflow-hidden">
                <div class="p-6">
                    <img src="data:image/jpeg;base64,' . base64_encode($offer["image_url"]) . '" alt="car Image" style="width: 160px; height: 160px;">
                    <h3 class="text-lg font-bold text-blue-600">' . htmlspecialchars($offer['nom_modele']) . '</h3>
                    <p class="text-gray-600 mb-4">Catégorie: <span class="text-yellow-500 font-bold">' . htmlspecialchars($offer['nom_categorie']) . '</span></p>
                    <p class="text-gray-600 mb-4">Statut: <span class="text-yellow-500 font-extrabold">' . htmlspecialchars($offer['disponibilite']) . '</span></p>
                    <p class="text-gray-600 mb-4">Prix: <span class="text-yellow-500 font-bold">' . htmlspecialchars($offer['prix_journee']) . '€</span></p>
                    <button onclick="showReservationPopup(' . $offer["id_vehicule"] . ')" 
                        class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Réserver Maintenant
                    </button>
                </div>
            </div>';
        }
    } else {
        echo '<p class="text-center text-gray-600">Aucun véhicule trouvé.</p>';
    }
}
?>