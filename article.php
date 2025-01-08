<?php
require_once('Classes/db.php');
require_once('Classes/Article.php');

// Vérifier si l'ID est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: blog2.php');
    exit();
}

try {
    $pdo = DatabaseConnection::getInstance()->getConnection();
    
    // Récupérer les détails de l'article
    $query = "SELECT a.*, u.nom, u.prenom, t.nom AS theme_nom 
              FROM Articles a
              JOIN Utilisateurs u ON a.id_utilisateur = u.id_utilisateur
              JOIN Themes t ON a.id_theme = t.id_theme
              WHERE a.id_article = :id AND a.statut = 'Accepté'";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $_GET['id']]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        header('Location: blog2.php');
        exit();
    }
} catch (Exception $e) {
    die("Erreur lors de la récupération de l'article : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['titre']) ?> - Drive & Loc</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    
    <!-- Navbar -->
    
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
                <li><a href="#offers" class="hover:text-blue-500">Voitures</a></li>
                <li><a href="blog2.php" class="hover:text-blue-500">Blog</a></li>
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
                <li><a href="#offers" class="hover:text-blue-500">Voitures</a></li>
                <li><a href="blog2.php" class="hover:text-blue-500">Blog</a></li>
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

    <!-- Article Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Image de l'article -->
            <?php if (!empty($article['image_url']) && file_exists($article['image_url'])): ?>
            <img src="<?= htmlspecialchars($article['image_url']) ?>" alt="<?= htmlspecialchars($article['titre']) ?>"
                class="w-full h-96 object-cover">
            <?php else: ?>
            <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <?php endif; ?>

            <div class="p-8">
                <!-- Thème et Date -->
                <div class="flex items-center justify-between mb-6">
                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded">
                        <?= htmlspecialchars($article['theme_nom']) ?>
                    </span>
                    <time class="text-gray-500 text-sm">
                        <?= date('d/m/Y', strtotime($article['date_creation'])) ?>
                    </time>
                </div>

                <!-- Titre -->
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    <?= htmlspecialchars($article['titre']) ?>
                </h1>

                <!-- Auteur -->
                <div class="flex items-center mb-8">
                    <div class="bg-gray-200 rounded-full w-12 h-12 flex items-center justify-center">
                        <span class="text-xl"><?= strtoupper(substr($article['prenom'], 0, 1)) ?></span>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-900 font-medium">
                            <?= htmlspecialchars($article['prenom'] . ' ' . $article['nom']) ?>
                        </p>
                        <p class="text-gray-500 text-sm">Auteur</p>
                    </div>
                </div>

                <!-- Contenu de l'article -->
                <div class="prose max-w-none text-gray-700 leading-relaxed">
                    <?= nl2br(htmlspecialchars($article['contenu'])) ?>
                </div>

                <!-- Bouton retour -->
                <div class="mt-12">
                    <a href="blog2.php" class="inline-flex items-center text-blue-600 hover:text-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Retour aux articles
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
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

    <script>
    // Menu mobile toggle
    const menuToggle = document.getElementById('menuToggle');
    const navLinks = document.getElementById('navLinks');

    menuToggle.addEventListener('click', () => {
        navLinks.classList.toggle('hidden');
    });
    </script>
</body>

</html>