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
                <li><a href="voitures.php" class="hover:text-blue-500">Voitures</a></li>
                <li><a href="index.php#about" class="hover:text-blue-500">À propos</a></li>
                <li><a href="index.php#notre-public-cible" class="hover:text-blue-500">Notre Public</a></li>
            </ul>
            <div class="hidden md:flex space-x-4">
                <button id="openLogin" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Connexion
                </button>
                <button id="openRegister" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                    Inscription
                </button>
            </div>
        </nav>
        <div id="mobileMenu" class="hidden bg-white shadow-md">
            <ul class="flex flex-col space-y-2 py-4 px-6 text-gray-700">
                <li><a href="index.php" class="hover:text-blue-500">Accueil</a></li>
                <li><a href="voitures.php" class="hover:text-blue-500">Voitures</a></li>
                <li><a href="index.php#about" class="hover:text-blue-500">À propos</a></li>
                <li><a href="index.php#notre-public-cible" class="hover:text-blue-500">Notre Public</a></li>
            </ul>
            <div class="space-y-2 px-6">
                <button id="openLoginMobile"
                    class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Connexion
                </button>
                <button id="openRegisterMobile"
                    class="w-full px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                    Inscription
                </button>
            </div>
        </div>
    </header>

    <section id="offers" class="bg-white py-16">
    <div class="container mx-auto px-6 lg:px-16">
        <h2 class="text-3xl font-bold text-center text-blue-600 mb-8">Nos top Offres Populaires</h2>
        
        <?php
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
            echo '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">';
            foreach ($offers as $offer) {
                echo '
                <div class="bg-gray-50 rounded-lg shadow-lg hover:shadow-xl transition duration-300 overflow-hidden">
                    <div class="p-6">
                        <img src="data:image/jpeg;base64,' . base64_encode($offer["image_url"]) . ' alt="car Image" style="width: 160px; height: 160px;">
                        <h3 class="text-lg font-bold text-blue-600">' . htmlspecialchars($offer['nom_modele']) . '</h3>
                        <p class="text-gray-600 mb-4"><span class="text-yellow-500 font-bold">' . htmlspecialchars($offer['nom_categorie']) . '</span></p>
                        <p class="text-gray-600 mb-4"><span class="text-yellow-500 font-extrabold">' . htmlspecialchars($offer['disponibilite']) . '</span></p>
                        <p class="text-gray-600 mb-4">À partir de <span class="text-yellow-500 font-bold">' . htmlspecialchars($offer['prix_journee']) . '€</span></p>
                        <a href="#auth" class="text-blue-500 hover:underline">Réservez Maintenant</a>
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
                <a href="?page=<?php echo $page - 1; ?>" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Précédent</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="px-4 py-2 <?php echo $i === $page ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'; ?> rounded-lg hover:bg-blue-500 hover:text-white"><?php echo $i; ?></a>
            <?php endfor; ?>
            
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Suivant</a>
            <?php endif; ?>
        </div>
    </div>
</section>

    <footer class="bg-gray-800 text-gray-300 py-10">
        <div class="container mx-auto px-6 lg:px-16">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0">
                    <div class="text-2xl font-extrabold text-white">🌍 Drive & Loc</div>
                    <p class="mt-2 text-gray-400">Votre compagnon pour louer des Voitures de luxe.</p>
                </div>
                <ul class="flex space-x-4">
                    <li><a href="#" class="text-gray-400 hover:text-white">Mentions Légales</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Politique de Confidentialité</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Nous Contacter</a></li>
                </ul>
            </div>
            <div class="text-center mt-8 text-gray-500">© 2024 Drive & Loc. Tous droits réservés.</div>
        </div>
    </footer>

</body>

</html>