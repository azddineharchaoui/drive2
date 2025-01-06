<?php

session_start();
if (!isset($_SESSION['user_id']) ||(isset($_SESSION['user_id']) && $_SESSION['role_id'] == 2)) {
  header("Location: ../index.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive & Loc</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-thumb {
        background: #3b82f6;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #2563eb;
    }
    </style>
</head>

<body class="bg-gradient-to-b from-blue-50 via-white to-gray-100 font-sans">
    <header class="bg-white shadow-md sticky top-0 z-50">
        <nav class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="text-2xl font-extrabold text-blue-600">🌍 Drive & Loc</div>
            <button id="menuToggle" class="md:hidden text-gray-700 focus:outline-none">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
            <ul id="navLinks" class="hidden md:flex space-x-6 text-gray-700">
                <li><a href="index.php" class="hover:text-blue-500">Accueil</a></li>
                <li><a href="#offers" class="hover:text-blue-500">Offres</a></li>
                <li><a href="#mes-reservations" class="hover:text-blue-500">Mes Réservations</a></li>
            </ul>
            <div class="hidden md:flex space-x-4">
                <button id="profileButton" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    Mon Profil
                </button>
                <form action="logout.php" method="POST">
                    <button id="logoutButtonMobile" type="submit" name="submit"
                        class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                        Déconnexion
                    </button>
                </form>
            </div>
        </nav>
        <div id="mobileMenu" class="hidden bg-white shadow-md">
            <ul class="flex flex-col space-y-2 py-4 px-6 text-gray-700">
                <li><a href="index.php" class="hover:text-blue-500">Accueil</a></li>
                <li><a href="#offers" class="hover:text-blue-500">Offres</a></li>
                <li><a href="#mes-reservations" class="hover:text-blue-500">Mes Réservations</a></li>
            </ul>
            <div class="space-y-2 px-6">
                <button id="profileButtonMobile"
                    class="w-full px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    Mon Profil
                </button>
                <form action="logout.php" method="POST">
                    <button id="logoutButtonMobile" type="submit" name="submit"
                        class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div id="profileModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Modifier Mon Profil</h2>
            <form id="profileForm">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-semibold text-gray-700">Nom</label>
                    <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded-lg"
                        placeholder="Votre nom">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg"
                        placeholder="Votre email">
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="button" id="closeModal"
                        class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    </div>


    <section id="mes-reservations" class="py-16 bg-gray-50">
        <div class="container mx-auto px-6 lg:px-16 text-center">
            <h2 class="text-3xl font-bold text-blue-600 mb-6">Mes Réservations</h2>
            <div class="flex gap-2 flex-wrap justify-center">
                <?php
require_once './Classes/Reservation.php';
$res = new Reservation();
$reservs = $res->listerReservationsParId($_SESSION['user_id']);
if (!empty($reservs)) {
    foreach ($reservs as $reserv) {
        echo '
        <div class="bg-gray-50 rounded-lg shadow-lg hover:shadow-xl transition duration-300 overflow-hidden w-80">
            <div class="p-6 space-y-3">
                <div class="flex flex-col space-y-2">
                    <h3 class="text-xl font-semibold text-gray-800">Voiture: ' . htmlspecialchars($reserv['nom_modele']) . '</h3>
                    
                    <div class="text-gray-600">
                        <span class="font-medium">Prix par jour:</span>
                        <span class="text-yellow-500 font-bold">' . htmlspecialchars($reserv['prix_journee']) . '€</span>
                    </div>
                    
                    <div class="text-gray-600">
                        <span class="font-medium">Date de début:</span>
                        <span class="text-yellow-500 font-bold">' . htmlspecialchars($reserv['date_debut']) . '</span>
                    </div>
                    
                    <div class="text-gray-600">
                        <span class="font-medium">Date de fin:</span>
                        <span class="text-yellow-500 font-bold">' . htmlspecialchars($reserv['date_fin']) . '</span>
                    </div>
                    
                    <div class="text-gray-600">
                        <span class="font-medium">Lieu de retour:</span>
                        <span class="text-yellow-500 font-bold">' . htmlspecialchars($reserv['lieu_retour']) . '</span>
                    </div>
                    
                    <div class="text-gray-600">
                        <span class="font-medium">Statut:</span>
                        <span class="text-yellow-500 font-extrabold">' . htmlspecialchars($reserv['statut']) . '</span>
                    </div>
                </div>
                
                <form action="canceledRes.php" method="POST" class="mt-4">
                    <input type="hidden" name="id_res" value="' . htmlspecialchars($reserv['id_reservation']) . '">
                    <button type="submit" name="cancel_reservation" 
                        class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-300">
                        Annuler Réservation
                    </button>
                </form>
            </div>
        </div>';
    }
} else {
    echo '<p class="text-center text-gray-600">Vous n\'avez aucune réservation pour le moment.</p>';
}
?>
            </div>
        </div>
    </section>

    <section id="offers" class="bg-white py-16">
        <div class="container mx-auto px-6 lg:px-16">
            <h2 class="text-3xl font-bold text-center text-blue-600 mb-8">Nos top Voitures Populaires</h2>
            <input type="text" 
    id="live_search" 
    autocomplete="off" 
    placeholder="Rechercher votre voiture" 
    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300 mb-4">            <div id="searchresult" class="mt-4"></div>            <?php
        require_once('Classes/Vehicule.php');

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 6; 
        $offset = ($page - 1) * $limit;

        $totalOffers = count(Vehicule::listeVehicules());
        $totalPages = ceil($totalOffers / $limit);

        $pdo = DatabaseConnection::getInstance()->getConnection();
        $query = "SELECT * FROM Vehicules as v INNER JOIN Categories as c ON v.id_categorie = c.id_categorie LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $offers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($offers)) {
            echo '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 ">';
            foreach ($offers as $offer) {
                echo '
                <div class="flex justify-center bg-gray-50 rounded-lg shadow-lg hover:shadow-xl transition duration-300 overflow-hidden">
                    <div class="p-6">
                        <img src="data:image/jpeg;base64,' . base64_encode($offer["image_url"]) . ' alt="car Image" style="width: 200px; height: 160px;">
                        <h3 class="text-lg font-bold text-blue-600">' . htmlspecialchars($offer['nom_modele']) . '</h3>
                        <p class="text-gray-600 mb-4"><span class="text-yellow-500 font-bold">' . htmlspecialchars($offer['nom_categorie']) . '</span></p>
                        <p class="text-gray-600 mb-4"><span class="text-yellow-500 font-extrabold">' . htmlspecialchars($offer['disponibilite']) . '</span></p>
                        <p class="text-gray-600 mb-4">À partir de <span class="text-yellow-500 font-bold">' . htmlspecialchars($offer['prix_journee']) . '€</span></p>
<button onclick="showReservationPopup(' . $offer["id_vehicule"] . ' )">Réservez Maintenant</button>
            <form id="reservationForm" action="reserver.php" method="POST" class="hidden">
                <input type="hidden" name="id_voiture" id="id_voiture">
                <input type="hidden" name="date_debut" id="date_debut">
                <input type="hidden" name="date_fin" id="date_fin">
                <input type="hidden" name="lieu_depart" id="lieu_depart">
                <input type="hidden" name="lieu_retour" id="lieu_retour">
            </form>
        </div>
        </div>';
        }
        echo '</div>';
        } else {
        echo '<p class="text-center text-gray-600">Aucune offre disponible pour le moment.</p>';
        }
        ?>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center space-x-4">
                <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Précédent</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>"
                    class="px-4 py-2 <?php echo $i === $page ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'; ?> rounded-lg hover:bg-blue-500 hover:text-white"><?php echo $i; ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Suivant</a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <footer class="bg-gray-800 text-gray-300 py-10">
        <div class="container mx-auto px-6 lg:px-16">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0">
                    <div class="text-2xl font-extrabold text-white">🌍 VoyagePro</div>
                    <p class="mt-2 text-gray-400">Votre compagnon pour des voyages inoubliables.</p>
                </div>
                <ul class="flex space-x-4">
                    <li><a href="#" class="text-gray-400 hover:text-white">Mentions Légales</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Politique de Confidentialité</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Nous Contacter</a></li>
                </ul>
            </div>
            <div class="text-center mt-8 text-gray-500">© 2024 VoyagePro. Tous droits réservés.</div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
// Menu mobile toggle
const menuToggle = document.getElementById('menuToggle');
const mobileMenu = document.getElementById('mobileMenu');
const navLinks = document.getElementById('navLinks');
menuToggle.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
});

// Profile modal handling
const profileButton = document.getElementById('profileButton');
const closeModalButton = document.getElementById('closeModal');
const profileModal = document.getElementById('profileModal');
const profileButtonMobile = document.getElementById('profileButtonMobile');

profileButton.addEventListener('click', () => {
    profileModal.classList.remove('hidden');
});

profileButtonMobile.addEventListener('click', () => {
    profileModal.classList.remove('hidden');
});

closeModalButton.addEventListener('click', () => {
    profileModal.classList.add('hidden');
});

// Profile form handling
document.getElementById('profileForm').addEventListener('submit', (e) => {
    e.preventDefault();
    console.log('Profile updated');
    profileModal.classList.add('hidden');
});

// Reservation popup
function showReservationPopup(idVehicule) {
    Swal.fire({
        title: 'Réservation',
        html: `
            <input type="date" id="swal-date_debut" class="swal2-input" placeholder="Date de début" required>
            <input type="date" id="swal-date_fin" class="swal2-input" placeholder="Date de fin" required>
            <input type="text" id="swal-lieu_depart" class="swal2-input" placeholder="Lieu de départ" required>
            <input type="text" id="swal-lieu_retour" class="swal2-input" placeholder="Lieu de retour" required>
        `,
        showCancelButton: true,
        confirmButtonText: 'Réserver',
        cancelButtonText: 'Annuler',
        preConfirm: () => {
            const date_debut = document.getElementById('swal-date_debut').value;
            const date_fin = document.getElementById('swal-date_fin').value;
            const lieu_depart = document.getElementById('swal-lieu_depart').value;
            const lieu_retour = document.getElementById('swal-lieu_retour').value;

            if (!date_debut || !date_fin || !lieu_depart || !lieu_retour) {
                Swal.showValidationMessage('Veuillez remplir tous les champs');
                return false;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'reserver.php';

            const fields = {
                'id_voiture': idVehicule,
                'date_debut': date_debut,
                'date_fin': date_fin,
                'lieu_depart': lieu_depart,
                'lieu_retour': lieu_retour
            };

            for (const [name, value] of Object.entries(fields)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = value;
                form.appendChild(input);
            }

            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Live search functionality
$(document).ready(function(){
    $("#live_search").keyup(function(){
        var input = $(this).val();
        if(input != ""){
            $.ajax({
                url: "livesearch.php",
                method: "POST",
                data: {input: input},
                success: function(data){
                    $("#searchresult").html(data);
                    $("#searchresult").css("display", "block");
                    $("#offers .grid").hide();
                }
            });
        } else {
            $("#searchresult").css("display", "none");
            $("#offers .grid").show();
        }
    });
});
</script>
</body>

</html>