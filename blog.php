<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tailwind Blog Template</title>
    <meta name="author" content="David Grzyb">
    <meta name="description" content="">

    <!-- Tailwind -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Karla:400,700&display=swap');

        .font-family-karla {
            font-family: karla;
        }
    </style>

    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" integrity="sha256-KzZiKy0DWYsnwMF+X1DvQngQ2/FxF7MF3Ff72XcpuPs=" crossorigin="anonymous"></script>
</head>
<body class="bg-white font-family-karla">

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
                <li><a href="blog.php" class="hover:text-blue-500">Blog</a></li>
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
                <li><a href="blog.php" class="hover:text-blue-500">Blog</a></li>
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


    <!-- Text Header -->
    <section class="w-full container mx-auto">
        <div class="flex flex-col items-center py-12">
            <a class="font-bold text-gray-800 uppercase hover:text-gray-700 text-5xl" href="#">
                Drive & Loc Blog
            </a>
            <p class="text-lg text-gray-600">
                Un blog dédié pour les passionnés par tous ce qui est automobile , voitures , et pièces de réparation.
            </p>
        </div>
    </section>

    <!-- Topic Nav -->
    <nav class="w-full py-4 border-t border-b bg-gray-100" x-data="{ open: false }">
        <div class="block sm:hidden">
            <a
                href="#"
                class="block md:hidden text-base font-bold uppercase text-center flex justify-center items-center"
                @click="open = !open"
            >
                Topics <i :class="open ? 'fa-chevron-down': 'fa-chevron-up'" class="fas ml-2"></i>
            </a>
        </div>
        <div :class="open ? 'block': 'hidden'" class="w-full flex-grow sm:flex sm:items-center sm:w-auto">
            <div class="w-full container mx-auto flex flex-col sm:flex-row items-center justify-center text-sm font-bold uppercase mt-0 px-6 py-2">
                <a href="#" class="hover:bg-gray-400 rounded py-2 px-4 mx-2">Technology</a>
                <a href="#" class="hover:bg-gray-400 rounded py-2 px-4 mx-2">Automotive</a>
                <a href="#" class="hover:bg-gray-400 rounded py-2 px-4 mx-2">Finance</a>
                <a href="#" class="hover:bg-gray-400 rounded py-2 px-4 mx-2">Politics</a>
                <a href="#" class="hover:bg-gray-400 rounded py-2 px-4 mx-2">Culture</a>
                <a href="#" class="hover:bg-gray-400 rounded py-2 px-4 mx-2">Sports</a>
            </div>
        </div>
    </nav>


    <div class="container mx-auto flex flex-wrap py-6">

        <!-- Posts Section -->
        <<!-- Dans la section "Posts Section" -->
<section class="w-full md:w-2/3 flex flex-col items-center px-3">
    <?php
    require_once('./Classes/Article.php');
    $articles = Article::listerArticles();
    
    foreach($articles as $article): 
    ?>
    <article class="flex flex-col shadow my-4">
        <div class="bg-white flex flex-col justify-start p-6">
            <a href="#" class="text-blue-700 text-sm font-bold uppercase pb-4"><?php echo htmlspecialchars($article['theme']); ?></a>
            <a href="#" class="text-3xl font-bold hover:text-gray-700 pb-4"><?php echo htmlspecialchars($article['titre']); ?></a>
            <p href="#" class="text-sm pb-3">
                Publié le <?php echo date('d/m/Y', strtotime($article['date_creation'])); ?>
            </p>
            <div class="pb-6">
                <?php echo nl2br(htmlspecialchars($article['contenu'])); ?>
            </div>
            <a href="article.php?id=<?php echo $article['id_article']; ?>" class="uppercase text-gray-800 hover:text-black">
                Lire la suite <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </article>
    <?php endforeach; ?>
</section>


<div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gestion des Articles</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addArticleModal">
                <i class="fas fa-plus"></i> Nouvel Article
            </button>
        </div>

        
<!-- Modal Ajouter Article -->
<div class="fixed inset-0 z-50 hidden overflow-y-auto" id="addArticleModal" aria-hidden="true">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl">
            <form id="addArticleForm" action="../manage_article.php" method="POST" class="p-6">
                <input type="hidden" name="action" value="create">
                <div class="flex justify-between items-center border-b pb-4 mb-4">
                    <h5 class="text-xl font-bold">Nouvel Article</h5>
                    <button type="button" class="text-gray-500 hover:text-gray-800" data-modal-hide="addArticleModal">
                        &times;
                    </button>
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="titre" class="block text-sm font-medium text-gray-700">Titre</label>
                        <input type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="titre" name="titre" required>
                    </div>
                    <div>
                        <label for="id_theme" class="block text-sm font-medium text-gray-700">Thème</label>
                        <select class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="id_theme" name="id_theme" required>
                            <option value="1">Technology</option>
                            <option value="2">Automotive</option>
                            <option value="3">Finance</option>
                            <option value="4">Politics</option>
                            <option value="5">Culture</option>
                            <option value="6">Sports</option>
                        </select>
                    </div>
                    <div>
                        <label for="contenu" class="block text-sm font-medium text-gray-700">Contenu</label>
                        <textarea class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="contenu" name="contenu" rows="10" required></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300" data-modal-hide="addArticleModal">Annuler</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">Publier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifier Article -->
<div class="fixed inset-0 z-50 hidden overflow-y-auto" id="editArticleModal" aria-hidden="true">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl">
            <form id="editArticleForm" action="manage_article.php" method="POST" class="p-6">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id_article" id="edit_id_article">
                <div class="flex justify-between items-center border-b pb-4 mb-4">
                    <h5 class="text-xl font-bold">Modifier l'Article</h5>
                    <button type="button" class="text-gray-500 hover:text-gray-800" data-modal-hide="editArticleModal">
                        &times;
                    </button>
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="edit_titre" class="block text-sm font-medium text-gray-700">Titre</label>
                        <input type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="edit_titre" name="titre" required>
                    </div>
                    <div>
                        <label for="edit_id_theme" class="block text-sm font-medium text-gray-700">Thème</label>
                        <select class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="edit_id_theme" name="id_theme" required>
                            <option value="1">Technology</option>
                            <option value="2">Automotive</option>
                            <option value="3">Finance</option>
                            <option value="4">Politics</option>
                            <option value="5">Culture</option>
                            <option value="6">Sports</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_contenu" class="block text-sm font-medium text-gray-700">Contenu</label>
                        <textarea class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="edit_contenu" name="contenu" rows="10" required></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300" id="editArticleModal">Annuler</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Confirmation Suppression -->
<div class="fixed inset-0 z-50 hidden overflow-y-auto" id="deleteArticleModal" aria-hidden="true">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
            <form id="deleteArticleForm" action="manage_article.php" method="POST" class="p-6">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id_article" id="delete_id_article">
                <div class="flex justify-between items-center border-b pb-4 mb-4">
                    <h5 class="text-xl font-bold">Confirmer la suppression</h5>
                    <button type="button" class="text-gray-500 hover:text-gray-800" data-modal-hide="deleteArticleModal">
                        &times;
                    </button>
                </div>
                <div>
                    <p>Êtes-vous sûr de vouloir supprimer l'article "<span id="delete_article_title"></span>" ?</p>
                </div>
                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300" data-modal-hide="deleteArticleModal">Annuler</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded hover:bg-red-700">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>

        <!-- Sidebar Section -->
        <aside class="w-full md:w-1/3 flex flex-col items-center px-3">

            <div class="w-full bg-white shadow flex flex-col my-4 p-6">
                <p class="text-xl font-semibold pb-5">About Us</p>
                <p class="pb-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas mattis est eu odio sagittis tristique. Vestibulum ut finibus leo. In hac habitasse platea dictumst.</p>
                <a href="#" class="w-full bg-blue-800 text-white font-bold text-sm uppercase rounded hover:bg-blue-700 flex items-center justify-center px-2 py-3 mt-4">
                    Get to know us
                </a>
            </div>

            <div class="w-full bg-white shadow flex flex-col my-4 p-6">
                <p class="text-xl font-semibold pb-5">Instagram</p>
                <div class="grid grid-cols-3 gap-3">
                    <img class="hover:opacity-75" src="https://source.unsplash.com/collection/1346951/150x150?sig=1">
                    <img class="hover:opacity-75" src="https://source.unsplash.com/collection/1346951/150x150?sig=2">
                    <img class="hover:opacity-75" src="https://source.unsplash.com/collection/1346951/150x150?sig=3">
                    <img class="hover:opacity-75" src="https://source.unsplash.com/collection/1346951/150x150?sig=4">
                    <img class="hover:opacity-75" src="https://source.unsplash.com/collection/1346951/150x150?sig=5">
                    <img class="hover:opacity-75" src="https://source.unsplash.com/collection/1346951/150x150?sig=6">
                    <img class="hover:opacity-75" src="https://source.unsplash.com/collection/1346951/150x150?sig=7">
                    <img class="hover:opacity-75" src="https://source.unsplash.com/collection/1346951/150x150?sig=8">
                    <img class="hover:opacity-75" src="https://source.unsplash.com/collection/1346951/150x150?sig=9">
                </div>
                <a href="#" class="w-full bg-blue-800 text-white font-bold text-sm uppercase rounded hover:bg-blue-700 flex items-center justify-center px-2 py-3 mt-6">
                    <i class="fab fa-instagram mr-2"></i> Follow @dgrzyb
                </a>
            </div>

        </aside>

    </div>

    <footer class="w-full border-t bg-white pb-12">
        <div
            class="relative w-full flex items-center invisible md:visible md:pb-12"
            x-data="getCarouselData()"
        >
            <button
                class="absolute bg-blue-800 hover:bg-blue-700 text-white text-2xl font-bold hover:shadow rounded-full w-16 h-16 ml-12"
                x-on:click="decrement()">
                &#8592;
            </button>
            <template x-for="image in images.slice(currentIndex, currentIndex + 6)" :key="images.indexOf(image)">
                <img class="w-1/6 hover:opacity-75" :src="image">
            </template>
            <button
                class="absolute right-0 bg-blue-800 hover:bg-blue-700 text-white text-2xl font-bold hover:shadow rounded-full w-16 h-16 mr-12"
                x-on:click="increment()">
                &#8594;
            </button>
        </div>
        <div class="w-full container mx-auto flex flex-col items-center">
            <div class="flex flex-col md:flex-row text-center md:text-left md:justify-between py-6">
                <a href="#" class="uppercase px-3">About Us</a>
                <a href="#" class="uppercase px-3">Privacy Policy</a>
                <a href="#" class="uppercase px-3">Terms & Conditions</a>
                <a href="#" class="uppercase px-3">Contact Us</a>
            </div>
            <div class="uppercase pb-6">&copy; myblog.com</div>
        </div>
    </footer>

    <script>
        function getCarouselData() {
            return {
                currentIndex: 0,
                images: [
                    'https://source.unsplash.com/collection/1346951/800x800?sig=1',
                    'https://source.unsplash.com/collection/1346951/800x800?sig=2',
                    'https://source.unsplash.com/collection/1346951/800x800?sig=3',
                    'https://source.unsplash.com/collection/1346951/800x800?sig=4',
                    'https://source.unsplash.com/collection/1346951/800x800?sig=5',
                    'https://source.unsplash.com/collection/1346951/800x800?sig=6',
                    'https://source.unsplash.com/collection/1346951/800x800?sig=7',
                    'https://source.unsplash.com/collection/1346951/800x800?sig=8',
                    'https://source.unsplash.com/collection/1346951/800x800?sig=9',
                ],
                increment() {
                    this.currentIndex = this.currentIndex === this.images.length - 6 ? 0 : this.currentIndex + 1;
                },
                decrement() {
                    this.currentIndex = this.currentIndex === this.images.length - 6 ? 0 : this.currentIndex - 1;
                },
            }
        }
 document.addEventListener('DOMContentLoaded', () => {
    // Gestion du modal de modification
    const editArticleModal = document.getElementById('editArticleModal');
    const editModalOverlay = editArticleModal.querySelector('.fixed');

    document.querySelectorAll('.editArticleBtn').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const titre = button.getAttribute('data-titre');
            const theme = button.getAttribute('data-theme');
            const contenu = button.getAttribute('data-contenu');

            document.getElementById('edit_id_article').value = id;
            document.getElementById('edit_titre').value = titre;
            document.getElementById('edit_id_theme').value = theme;
            document.getElementById('edit_contenu').value = contenu;

            // Affiche le modal
            editArticleModal.classList.remove('hidden');
            editArticleModal.classList.add('flex');
        });
    });

    // Fermeture du modal de modification
    editModalOverlay.addEventListener('click', (e) => {
        if (e.target === editModalOverlay) {
            editArticleModal.classList.add('hidden');
            editArticleModal.classList.remove('flex');
        }
    });

    // Gestion du modal de suppression
    const deleteArticleModal = document.getElementById('deleteArticleModal');
    const deleteModalOverlay = deleteArticleModal.querySelector('.fixed');

    document.querySelectorAll('.deleteArticleBtn').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const titre = button.getAttribute('data-titre');

            document.getElementById('delete_id_article').value = id;
            document.getElementById('delete_article_title').textContent = titre;

            // Affiche le modal
            deleteArticleModal.classList.remove('hidden');
            deleteArticleModal.classList.add('flex');
        });
    });

    // Fermeture du modal de suppression
    deleteModalOverlay.addEventListener('click', (e) => {
        if (e.target === deleteModalOverlay) {
            deleteArticleModal.classList.add('hidden');
            deleteArticleModal.classList.remove('flex');
        }
    });

    // Boutons de fermeture dans les modals
    document.querySelectorAll('[data-modal-hide]').forEach(button => {
        button.addEventListener('click', () => {
            const modal = button.closest('.fixed');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    });
});

    </script>

</body>
</html>