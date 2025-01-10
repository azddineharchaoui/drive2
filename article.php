<?php
require_once('Classes/db.php');
require_once('Classes/Article.php');
    session_start();
// Vérifier si l'ID est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: blog2.php');
    exit();
}

try {
    $pdo = DatabaseConnection::getInstance()->getConnection();
    
    // Récupérer les détails de l'article avec les tags
    $query = "SELECT a.*, u.nom, u.prenom, t.nom AS theme_nom,
              GROUP_CONCAT(tags.nom) as article_tags
              FROM Articles a
              JOIN Utilisateurs u ON a.id_utilisateur = u.id_utilisateur
              JOIN Themes t ON a.id_theme = t.id_theme
              LEFT JOIN Article_Tag at ON a.id_article = at.id_article
              LEFT JOIN Tags tags ON at.id_tag = tags.id_tag
              WHERE a.id_article = :id AND a.statut = 'Accepté'
              GROUP BY a.id_article";
    
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
                <!-- Section des tags -->
                <?php if (!empty($article['article_tags'])): ?>
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach (explode(',', $article['article_tags']) as $tag): ?>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors">
                            #<?= htmlspecialchars(trim($tag)) ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

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

        <!-- Section des commentaires -->
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md mt-8 p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Commentaires</h2>

            <!-- Affichage des commentaires existants -->
            <?php
    try {
        $query = "SELECT c.*, u.nom, u.prenom 
                  FROM Commentaires c 
                  INNER JOIN Utilisateurs u ON c.id_utilisateur = u.id_utilisateur 
                  WHERE c.id_article = :id_article 
                  ORDER BY c.created_at DESC";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id_article' => $_GET['id']]);
        $commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($commentaires): ?>
            <div class="space-y-6 mb-8">
                <?php foreach ($commentaires as $commentaire): ?>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center mb-2">
                        <div class="bg-blue-100 rounded-full w-8 h-8 flex items-center justify-center">
                            <span class="text-sm font-medium text-blue-800">
                                <?= strtoupper(substr($commentaire['prenom'], 0, 1)) ?>
                            </span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">
                                <?= htmlspecialchars($commentaire['prenom'] . ' ' . $commentaire['nom']) ?>
                            </p>
                            <p class="text-xs text-gray-500">
                                <?= date('d/m/Y H:i', strtotime($commentaire['created_at'])) ?>
                            </p>
                        </div>
                    </div>
                    <p class="text-gray-700">
                        <?= nl2br(htmlspecialchars($commentaire['contenu'])) ?>
                    </p>
                </div>
                <?php if (isset($_SESSION['user_id']) && $commentaire['id_utilisateur'] == $_SESSION['user_id']): ?>
                <div class="flex space-x-4 mt-2">
                    <!-- Bouton Modifier -->
                    <button onclick="toggleEditForm(<?= $commentaire['id_commentaire'] ?>)"
                        class="text-sm text-blue-600 hover:text-blue-800">
                        Modifier
                    </button>

                    <!-- Formulaire de modification (caché par défaut) -->
                    <form id="editForm<?= $commentaire['id_commentaire']; ?>" action="manage_commentaire.php"
                        method="POST" class="hidden mt-2 w-full">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id_commentaire" value="<?= $commentaire['id_commentaire'] ?>">
                        <input type="hidden" name="id_article" value="<?= $_GET['id'] ?>">
                        <textarea name="contenu"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required><?= htmlspecialchars($commentaire['contenu']) ?></textarea>
                        <div class="flex space-x-2 mt-2">
                            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Enregistrer
                            </button>
                            <button type="button" onclick="toggleEditForm(<?= $commentaire['id_commentaire'] ?>)"
                                class="px-3 py-1 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                                Annuler
                            </button>
                        </div>
                    </form>

                    <!-- Formulaire de suppression -->
                    <form action="manage_commentaire.php" method="POST" class="inline"
                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_commentaire" value="<?= $commentaire['id_commentaire'] ?>">
                        <input type="hidden" name="id_article" value="<?= $_GET['id'] ?>">
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                            Supprimer
                        </button>
                    </form>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-gray-500 mb-8">Aucun commentaire pour le moment. Soyez le premier à commenter !</p>
            <?php endif; ?>

            <?php } catch (Exception $e) {
        echo "<p class='text-red-500'>Erreur lors de la récupération des commentaires : " . htmlspecialchars($e->getMessage()) . "</p>";
    } ?>

            <!-- Formulaire pour ajouter un commentaire -->
            <form action="manage_commentaire.php" method="POST" class="space-y-4">
                <input type="hidden" name="id_article" value="<?= htmlspecialchars($_GET['id']) ?>">
                <input type="hidden" name="action" value="create">

                <div>
                    <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-2">
                        Votre commentaire
                    </label>
                    <textarea id="commentaire" name="contenu" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required></textarea>
                </div>

                <button type="submit"
                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Publier le commentaire
                </button>
            </form>
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
    
        function toggleEditForm(commentId) {
            const form = document.getElementById('editForm' + commentId);
            if (form.classList.contains('hidden')) {
                document.querySelectorAll('[id^="editForm"]').forEach(f => {
                    if (f.id !== 'editForm' + commentId) {
                        f.classList.add('hidden');
                    }
                });
                form.classList.remove('hidden');
            } else {
                form.classList.add('hidden');
            }
        }

    <?php if (isset($_SESSION['success'])): ?>
    alert("<?= addslashes($_SESSION['success']) ?>");
    <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    alert("<?= addslashes($_SESSION['error']) ?>");
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    </script>
</body>

</html>