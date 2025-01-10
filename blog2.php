<?php
require_once('Classes/db.php');

try {
    $pdo = DatabaseConnection::getInstance()->getConnection();
    $queryThemes = "SELECT * FROM Themes ORDER BY nom";
    $stmtThemes = $pdo->query($queryThemes);
    $themes = $stmtThemes->fetchAll(PDO::FETCH_ASSOC);

    $selectedTheme = isset($_GET['theme']) ? $_GET['theme'] : null;

    $queryArticles = "SELECT a.id_article, a.titre, a.contenu, a.date_creation, a.image_url, 
                  u.nom, u.prenom, t.nom AS theme,
                  GROUP_CONCAT(tg.nom) as tags
                  FROM Articles a
                  JOIN Utilisateurs u ON a.id_utilisateur = u.id_utilisateur
                  JOIN Themes t ON a.id_theme = t.id_theme
                  LEFT JOIN Article_Tag at ON a.id_article = at.id_article
                  LEFT JOIN Tags tg ON at.id_tag = tg.id_tag
                  WHERE a.statut = 'Accepté'";
    
    if ($selectedTheme) {
        $queryArticles .= " AND a.id_theme = :theme_id";
    }
    
    $queryArticles .= " GROUP BY a.id_article ORDER BY a.date_creation DESC";
    
    $stmtArticles = $pdo->prepare($queryArticles);
    if ($selectedTheme) {
        $stmtArticles->bindParam(':theme_id', $selectedTheme, PDO::PARAM_INT);
    }
    $stmtArticles->execute();
    $articles = $stmtArticles->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Articles</title>
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
                <li><a href="location.php" class="hover:text-blue-500">Voitures</a></li>
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
                <li><a href="location.php" class="hover:text-blue-500">Voitures</a></li>
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

    <div class="flex justify-center items-center mb-6">
        <button onclick="openModal()"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
            + Ajouter un article
        </button>
    </div>

    <!-- Modal -->
    <div id="articleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-2xl font-bold text-gray-800">Proposer un article</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form action="add_article.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label for="theme" class="block text-sm font-medium text-gray-700">Thème</label>
                    <select name="theme" id="theme" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Sélectionnez un thème</option>
                        <?php foreach ($themes as $theme): ?>
                        <option value="<?= htmlspecialchars($theme['id_theme']) ?>">
                            <?= htmlspecialchars($theme['nom']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700">Titre</label>
                    <input type="text" name="titre" id="titre" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Image de l'article</label>
                    <div class="mt-1 flex items-center">
                        <label
                            class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <span id="fileName">Choisir une image</span>
                            <input type="file" name="image" id="image" accept="image/*" class="sr-only"
                                onchange="updateFileName(this)">
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF jusqu'à 5MB</p>
                </div>

                <div>
                    <label for="contenu" class="block text-sm font-medium text-gray-700">Contenu</label>
                    <textarea name="contenu" id="contenu" rows="6" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <div class="mb-4">
                    <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                    <div id="tags-container"
                        class="flex flex-wrap items-center gap-2 p-2 border rounded-md bg-white min-h-[42px]">
                        <div id="tags-list" class="flex flex-wrap gap-2"></div>
                        <input type="text" id="tag-input"
                            class="flex-1 min-w-[120px] outline-none border-none bg-transparent focus:ring-0 text-sm"
                            placeholder="Ajouter un tag...">
                    </div>
                    <input type="hidden" name="tags" id="tags-hidden">
                    <p class="text-xs text-gray-500 mt-1">Appuyez sur Entrée ou utilisez une virgule pour ajouter un tag
                    </p>
                </div>

                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Annuler
                    </button>
                    <button type="submit" name="submit_article"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Soumettre l'article
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Message de succès ou d'erreur -->
    <?php if (isset($_GET['success'])): ?>
    <div id="successMessage"
        class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-md">
        <span class="block sm:inline">Votre article a été soumis avec succès et est en attente de validation.</span>
        <button onclick="this.parentElement.remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
    <div id="errorMessage"
        class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-md">
        <span class="block sm:inline"><?= htmlspecialchars($_GET['error']) ?></span>
        <button onclick="this.parentElement.remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    <?php endif; ?>
    <!-- Main Content -->
    <main class="container mx-auto px-6 py-8">


        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Thèmes</h2>
            <div class="flex flex-wrap gap-3">
                <a href="blog2.php"
                    class="<?= !$selectedTheme ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' ?> px-4 py-2 rounded-full hover:bg-blue-600 hover:text-white transition-colors">
                    Tous les articles
                </a>
                <?php foreach ($themes as $theme): ?>
                <a href="blog2.php?theme=<?= htmlspecialchars($theme['id_theme']) ?>"
                    class="<?= $selectedTheme == $theme['id_theme'] ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' ?> px-4 py-2 rounded-full hover:bg-blue-600 hover:text-white transition-colors">
                    <?= htmlspecialchars($theme['nom']) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">
                <?= $selectedTheme ? 'Articles dans ' . htmlspecialchars($themes[array_search($selectedTheme, array_column($themes, 'id_theme'))]['nom']) : 'Derniers Articles' ?>
            </h2>
        </div>


        <!-- Articles Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (empty($articles)): ?>
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-600">Aucun article trouvé pour ce thème.</p>
                </div>
            <?php else: ?>
                <?php foreach ($articles as $article): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <!-- Image placeholder -->
                        <?php if (!empty($article['image_url']) && file_exists($article['image_url'])): ?>
                            <img src="<?= htmlspecialchars($article['image_url']) ?>"
                                alt="<?= htmlspecialchars($article['titre']) ?>" class="w-full h-48 object-cover">
                        <?php else: ?>
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        <?php endif; ?>
                        <div class="p-4">
                            <!-- Article Title -->
                            <h3 class="text-lg font-semibold text-gray-800 hover:text-blue-500">
                                <a href="article.php?id=<?= htmlspecialchars($article['id_article']) ?>">
                                    <?= htmlspecialchars($article['titre']) ?>
                                </a>
                            </h3>
                            <!-- Metadata -->
                            <p class="text-sm text-gray-600 mt-2">
                                By <span class="font-medium"><?= htmlspecialchars($article['nom'] . ' ' . $article['prenom']) ?></span>
                                on <span class="text-gray-500"><?= htmlspecialchars($article['date_creation']) ?></span>
                                | Theme: <span class="text-blue-500"><?= htmlspecialchars($article['theme']) ?></span>
                            </p>
                            <!-- Article Excerpt -->
                            <p class="text-gray-700 mt-4">
                                <?= substr(htmlspecialchars($article['contenu']), 0, 100) . '...' ?>
                            </p>

                            <!-- Read More Link -->
                            <div class="mt-4">
                                <a href="article.php?id=<?= htmlspecialchars($article['id_article']) ?>"
                                    class="text-blue-500 hover:underline font-medium">Read more →</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php if (empty($articles)): ?>
        <p class="text-gray-600 text-center mt-6">No articles found.</p>
        <?php endif; ?>

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
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const navLinks = document.getElementById('navLinks');
    menuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Profile modal handling
    const profileButton = document.getElementById('profileButton');
    // const closeModalButton = document.getElementById('closeModal');
    const profileModal = document.getElementById('profileModal');
    const profileButtonMobile = document.getElementById('profileButtonMobile');

    profileButton.addEventListener('click', () => {
        profileModal.classList.remove('hidden');
    });

    profileButtonMobile.addEventListener('click', () => {
        profileModal.classList.remove('hidden');
    });

    // closeModalButton.addEventListener('click', () => {
    //     profileModal.classList.add('hidden');
    // });

    // Profile form handling
    // document.getElementById('profileForm').addEventListener('submit', (e) => {
    //     e.preventDefault();
    //     console.log('Profile updated');
    //     profileModal.classList.add('hidden');
    // });

    function openModal() {
        document.getElementById('articleModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Empêche le défilement de la page
    }

    function closeModal() {
        document.getElementById('articleModal').classList.add('hidden');
        document.body.style.overflow = 'auto'; // Réactive le défilement de la page
    }

    document.getElementById('articleModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('articleModal').classList.contains('hidden')) {
            closeModal();
        }
    });

    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');

    if (successMessage) {
        setTimeout(() => {
            successMessage.remove();
        }, 5000);
    }

    if (errorMessage) {
        setTimeout(() => {
            errorMessage.remove();
        }, 5000);
    }

    function updateFileName(input) {
        const fileName = input.files[0]?.name || 'Choisir une image';
        document.getElementById('fileName').textContent = fileName;
    }

    const tagInput = document.getElementById('tag-input');
    const tagsContainer = document.getElementById('tags-container');
    const tagsList = document.getElementById('tags-list');
    const tagsHidden = document.getElementById('tags-hidden');
    let tags = [];

    function createTagElement(tagText) {
        const tagElement = document.createElement('div');
        tagElement.className = 'inline-flex items-center bg-blue-100 text-blue-700 rounded-full px-3 py-1 text-sm';
        tagElement.innerHTML = `
        <span>${tagText}</span>
        <button type="button" class="ml-2 text-blue-900 hover:text-blue-800 focus:outline-none" onclick="removeTag('${tagText}')">
            &times;
        </button>
    `;
        return tagElement;
    }

    function addTag(tagText) {
        tagText = tagText.trim();
        if (tagText && !tags.includes(tagText)) {
            tags.push(tagText);
            updateTagsDisplay();
        }
    }

    function removeTag(tagText) {
        tags = tags.filter(tag => tag !== tagText);
        updateTagsDisplay();
    }

    function updateTagsDisplay() {
        tagsList.innerHTML = '';

        tags.forEach(tag => {
            tagsList.appendChild(createTagElement(tag));
        });

        tagsHidden.value = tags.join(',');
    }

    tagInput.addEventListener('keydown', function(event) {
        if ((event.key === 'Enter' || event.key === ',') && this.value.trim()) {
            event.preventDefault();
            addTag(this.value);
            this.value = '';
        }
    });

    tagInput.addEventListener('paste', function(event) {
        event.preventDefault();
        const pastedText = (event.clipboardData || window.clipboardData).getData('text');
        const tagsArray = pastedText.split(',');
        tagsArray.forEach(tag => {
            if (tag.trim()) {
                addTag(tag);
            }
        });
    });
    </script>

</body>

</html>