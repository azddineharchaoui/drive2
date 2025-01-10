<?php 
    require_once('../Classes/Categorie.php');

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    :root {
        --sidebar-width: 250px;
    }

    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        padding-top: 1rem;
        background-color: #2c3e50;
        color: white;
        transition: all 0.3s;
        z-index: 1000;
        overflow-y: auto;
    }

    .main-content {
        margin-left: var(--sidebar-width);
        padding: 2rem;
        transition: all 0.3s;
        width: calc(100% - var(--sidebar-width));
        min-height: 100vh;
    }

    .main-content.active {
        margin-left: var(--sidebar-width);
        width: calc(100% - var(--sidebar-width));
    }

    .sidebar .nav-link {
        color: #ecf0f1;
        padding: 0.8rem 1rem;
        transition: all 0.3s;
    }

    .sidebar .nav-link:hover {
        background-color: #34495e;
        color: #fff;
    }

    .sidebar .nav-link.active {
        background-color: #3498db;
        color: #fff;
    }

    .sidebar .nav-link i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }

    .section-content {
        display: none;
        width: 100%;
        padding: 1rem;

    }

    .section-content .container {

        max-width: none;
        padding: 0;
    }

    .table-responsive {
        width: 100%;
        margin: 0;
    }

    .section-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .brand-name {
        font-size: 1.5rem;
        padding: 1rem;
        text-align: center;
        border-bottom: 1px solid #34495e;
        margin-bottom: 1rem;
    }

    .card {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .table-wrapper {
        background: white;
        padding: 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #3498db;
        color: white;
        font-weight: bold;
    }

    .btn-custom {
        background-color: #3498db;
        color: white;
    }

    .btn-custom:hover {
        background-color: #2980b9;
        color: white;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .sidebar {
            margin-left: calc(-1 * var(--sidebar-width));
        }

        .sidebar.active {
            margin-left: 0;
        }

        .main-content {
            margin-left: 0;
            width: 100%;

        }

        .main-content.active {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
        }
    }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand-name">
            <i class="fas fa-car"></i> Admin Panel
        </div>
        <nav class="nav flex-column">
            <a class="nav-link active" href="#dashboard" data-section="dashboard">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a class="nav-link" href="#reservations" data-section="reservations">
                <i class="fas fa-calendar-check"></i> Réservations
            </a>
            <a class="nav-link" href="#vehicules" data-section="vehicules">
                <i class="fas fa-car"></i> Véhicules
            </a>
            <a class="nav-link" href="#categories" data-section="categories">
                <i class="fas fa-tags"></i> Catégories
            </a>
            <a class="nav-link" href="#avis" data-section="avis">
                <i class="fas fa-comments"></i> Avis
            </a>
            <a class="nav-link" href="#themes" data-section="themes">
                <i class="fas fa-palette"></i> Thèmes
            </a>
            <a class="nav-link" href="#commentaires" data-section="commentaires">
                <i class="fas fa-comments"></i> Commentaires
            </a>
            <a class="nav-link" href="#articles" data-section="articles">
                <i class="fas fa-newspaper"></i> Articles
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Les différentes sections seront chargées ici -->
        <div id="dashboard" class="section-content active">
            <h2 class="mb-4">Dashboard Vue d'ensemble</h2>
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Réservations</h5>
                            <p class="card-text display-6">24</p>
                            <p class="text-success"><i class="fas fa-arrow-up"></i> +15% cette semaine</p>
                        </div>
                    </div>
                </div>
                <!-- Ajoutez d'autres cartes statistiques similaires -->
            </div>
        </div>

        <div id="reservations" class="section-content">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-calendar-check"></i>Reservations récentes
                </div>

                <?php 
                    require_once('../Classes/Reservation.php');
                    $reservations = Reservation::listerReservations();?>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php foreach ($reservations as $reservation): ?>
                    <div class="col">
                        <div class="card h-100 shadow">
                            <div class="card-body">
                                <h5 class="card-title">Réservation #<?= $reservation['id_reservation'] ?></h5>
                                <p class="card-text">
                                    <strong>Modèle :</strong> <?= $reservation['nom_modele'] ?><br>
                                    <strong>Date Début :</strong> <?= $reservation['date_debut'] ?><br>
                                    <strong>Date Fin :</strong> <?= $reservation['date_fin'] ?><br>
                                    <strong>Lieu Départ :</strong> <?= $reservation['lieu_depart'] ?><br>
                                    <strong>Lieu Retour :</strong> <?= $reservation['lieu_retour'] ?><br>
                                    <strong>Statut :</strong>
                                    <span
                                        class="badge bg-<?= $reservation['statut'] == 'en attente' ? 'warning text-dark' : ($reservation['statut'] == 'confirmée' ? 'success' : 'danger') ?>">
                                        <?= ucfirst($reservation['statut']) ?>
                                    </span><br>
                                </p>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <form method="POST" class="d-inline w-45">
                                    <input type="hidden" name="reservation_id"
                                        value="<?= $reservation['id_reservation'] ?>">
                                    <input type="hidden" name="action" value="accept">
                                    <button type="submit" class="btn btn-success w-100"
                                        <?= $reservation['statut'] !== 'en attente' ? 'disabled' : '' ?>>
                                        Accepter
                                    </button>
                                </form>
                                <form method="POST" class="d-inline w-45">
                                    <input type="hidden" name="reservation_id"
                                        value="<?= $reservation['id_reservation'] ?>">
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="btn btn-danger w-100"
                                        <?= $reservation['statut'] !== 'en attente' ? 'disabled' : '' ?>>
                                        Refuser
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

        <div id="vehicules" class="section-content">
            <!-- Manage Vehicules Section -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h2 class="text-center">Ajout de Véhicule</h2>
                </div>
                <div class="card-body">
                    <form action="ajouterVehicule.php" method="post" enctype="multipart/form-data">
                        <div id="car-container">
                            <input name="nbr_cars" id="nbr_cars" class="d-none">
                            <div class="car-item">
                                <div class="mb-3">
                                    <label for="nomModele0" class="form-label">Nom du Modèle :</label>
                                    <input type="text" class="form-control" name="nomModele0" id="nomModele0"
                                        placeholder="Entrez le modèle du véhicule" required>
                                </div>

                                <div class="mb-3">
                                    <label for="idCategorie0" class="form-label">Catégorie :</label>
                                    <select class="form-select" name="idCategorie0" id="idCategorie0" required>
                                        <option value="" disabled selected>Choisissez une catégorie</option>
                                        <?php
                                            $categories = Categorie::listerCategories();
                                            foreach ($categories as $categorie) {
                                                echo '<option value="' . htmlspecialchars($categorie['id_categorie']) . '">' . htmlspecialchars($categorie['nom_categorie']) . '</option>';
                                            }
                                        ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="prixJournee0" class="form-label">Prix par Journée :</label>
                                    <input type="number" class="form-control" name="prixJournee0" id="prixJournee0"
                                        step="0.01" placeholder="Entrez le prix par journée" required>
                                </div>

                                <div class="mb-3">
                                    <label for="disponibilite0" class="form-label">Disponibilité :</label>
                                    <select class="form-select" name="disponibilite0" id="disponibilite0" required>
                                        <option value="" disabled selected>Choisissez la disponibilité</option>
                                        <option value="Disponible">Disponible</option>
                                        <option value="Non disponible">Non disponible</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="imageUrl0" class="form-label">Image du Véhicule :</label>
                                    <input type="file" class="form-control" name="imageUrl0" id="imageUrl0"
                                        accept="image/*" required>
                                </div>
                            </div>


                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-secondary w-0" id="add_car">Ajouter plus</button>
                            <button type="submit" class="btn btn-primary w-0">Ajouter </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des Véhiculess -->
            <h3>Liste des Véhicules</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom du modèle</th>
                        <th>id_categorie</th>
                        <th>Prix de la journéé</th>
                        <th>Disponibilité</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                require_once("../Classes/Vehicule.php");

                $vehicules = Vehicule::listeVehicules();
                if (count($vehicules) > 0): ?>
                    <?php foreach ($vehicules as $vcl): ?>
                    <tr>
                        <td><?= $vcl['id_vehicule']; ?></td>
                        <td><?= $vcl['nom_modele']; ?></td>
                        <td><?= $vcl['id_categorie']; ?></td>
                        <td><?= $vcl['prix_journee']; ?></td>
                        <td><?= $vcl['disponibilite']; ?></td>
                        <td>
                            <button type="button" class="btn btn-success btn-sm editVoitureBtn"
                                data-id="<?= $vcl['id_vehicule']; ?>"
                                data-nom-modele="<?= htmlspecialchars($vcl['nom_modele'], ENT_QUOTES); ?>"
                                data-id-categorie="<?= $vcl['id_categorie']; ?>"
                                data-prix-journee="<?= $vcl['prix_journee']; ?>"
                                data-disponibilite="<?= $vcl['disponibilite']; ?>">
                                Modifier
                            </button>

                            <form method="POST" action="manage_vehicule.php" class="d-inline">
                                <button type="submit" name="delete_vehicule" value="<?= $vcl['id_vehicule']; ?>"
                                    class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Aucune activité trouvée.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Modal Modifier Vehicule -->
        <!-- Modal pour la modification d'un véhicule -->
        <div class="modal fade" id="editVehiculeModal" tabindex="-1" aria-labelledby="editVehiculeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editVehiculeForm" method="POST" action="manage_vehicule.php"
                        enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editVehiculeModalLabel">Modifier le Véhicule</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="edit_vehicule" value="1">
                            <input type="hidden" id="edit_id_vehicule" name="id_vehicule">

                            <div class="mb-3">
                                <label for="edit_nom_modele" class="form-label">Nom du Modèle :</label>
                                <input type="text" class="form-control" id="edit_nom_modele" name="nom_modele" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_id_categorie" class="form-label">Catégorie :</label>
                                <input type="number" class="form-control" id="edit_id_categorie" name="id_categorie"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_prix_journee" class="form-label">Prix par Journée :</label>
                                <input type="text" class="form-control" id="edit_prix_journee" name="prix_journee"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_disponibilite" class="form-label">Disponibilité :</label>
                                <select class="form-select" id="edit_disponibilite" name="disponibilite" required>
                                    <option value="Disponible">Disponible</option>
                                    <option value="Non disponible">Non disponible</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_image_url" class="form-label">Image (optionnel) :</label>
                                <input type="file" class="form-control" id="edit_image_url" name="image_url"
                                    accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-success">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <div id="categories" class="section-content">
            <div class="container mt-5">
                <h2>Gestion des Catégories</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom de la Catégorie</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

            $categories = Categorie::listerCategories();
            if (count($categories) > 0):
                foreach ($categories as $categorie):
            ?>
                        <tr>
                            <td><?= $categorie['id_categorie']; ?></td>
                            <td><?= htmlspecialchars($categorie['nom_categorie'], ENT_QUOTES); ?></td>
                            <td><?= htmlspecialchars($categorie['description'] ?? 'Non spécifiée', ENT_QUOTES); ?></td>
                            <td>

                                <button type="button" class="btn btn-success btn-sm editCategorieBtn"
                                    data-id="<?= $categorie['id_categorie']; ?>"
                                    data-nom="<?= htmlspecialchars($categorie['nom_categorie'], ENT_QUOTES); ?>"
                                    data-description="<?= htmlspecialchars($categorie['description'] ?? '', ENT_QUOTES); ?>">
                                    Modifier
                                </button>

                                <!-- Formulaire Supprimer -->
                                <form method="POST" action="manage_categorie.php" class="d-inline">
                                    <input type="hidden" name="delete_categorie"
                                        value="<?= $categorie['id_categorie']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        <?php
                endforeach;
            else:
            ?>
                        <tr>
                            <td colspan="4" class="text-center">Aucune catégorie trouvée.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Formulaire Ajouter une Catégorie -->
                <h3>Ajouter une Nouvelle Catégorie</h3>
                <form method="POST" action="manage_categorie.php">
                    <div class="mb-3">
                        <label for="nomCategorie" class="form-label">Nom de la Catégorie</label>
                        <input type="text" name="nom_categorie" id="nomCategorie" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="descriptionCategorie" class="form-label">Description</label>
                        <textarea name="description_categorie" id="descriptionCategorie" class="form-control"
                            rows="3"></textarea>
                    </div>
                    <button type="submit" name="add_categorie" class="btn btn-primary">Ajouter</button>
                </form>
            </div>

            <!-- Modale de modification categorie -->

            <div class="modal fade" id="editCategorieModal" tabindex="-1" aria-labelledby="editCategorieModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="manage_categorie.php">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editCategorieModalLabel">Modifier la Catégorie</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_categorie" id="editCategorieId">
                                <div class="mb-3">
                                    <label for="editNomCategorie" class="form-label">Nom de la Catégorie</label>
                                    <input type="text" name="nom_categorie" id="editNomCategorie" class="form-control"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="editDescriptionCategorie" class="form-label">Description</label>
                                    <textarea name="description_categorie" id="editDescriptionCategorie"
                                        class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" name="edit_categorie" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        <div id="avis" class="section-content">
            <!-- Section des avis -->
            <div class="container mt-5">
                <h2>Gestion des Avis</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID Véhicule</th>
                            <th>ID Utilisateur</th>
                            <th>Commentaire</th>
                            <th>Évaluation</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
            require_once('../Classes/Avis.php');
            $avisList = Avis::listerAvisParVehicule($_GET['vehicule_id'] ?? null); // Passer un ID si nécessaire
            foreach ($avisList as $avis) {
                echo "<tr>";
                echo "<td>{$avis['id_avis']}</td>";
                echo "<td>{$avis['id_vehicule']}</td>";
                echo "<td>{$avis['id_utilisateur']}</td>";
                echo "<td>{$avis['commentaire']}</td>";
                echo "<td>{$avis['evaluation']}</td>";
                echo "<td>
                        <button class='btn btn-primary btn-sm editAvisBtn' data-id='{$avis['id_avis']}' data-commentaire='{$avis['commentaire']}' data-evaluation='{$avis['evaluation']}'>Modifier</button>
                        <form method='POST' action='' style='display:inline-block;'>
                            <input type='hidden' name='delete_avis' value='{$avis['id_avis']}'>
                            <button type='submit' class='btn btn-danger btn-sm'>Supprimer</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
            ?>
                    </tbody>
                </table>
            </div>


            <div class="modal fade" id="editAvisModal" tabindex="-1" aria-labelledby="editAvisModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editAvisForm" action="manage_avis.php" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editAvisModalLabel">Modifier l'Avis</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="edit_avis" value="1">
                                <input type="hidden" id="edit_id_avis" name="id_avis">
                                <div class="mb-3">
                                    <label for="edit_commentaire" class="form-label">Commentaire</label>
                                    <textarea class="form-control" id="edit_commentaire" name="commentaire"
                                        required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_evaluation" class="form-label">Évaluation</label>
                                    <select class="form-select" id="edit_evaluation" name="evaluation" required>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        <div id="themes" class="section-content">
            <!-- Section des themes -->
            <h1>Gestion des Thèmes</h1>

            <?php if (isset($_GET['success'])): ?>
            <p class="success">
                <?php
    if ($_GET['success'] == 'added') echo "Thème ajouté avec succès !";
    if ($_GET['success'] == 'updated') echo "Thème mis à jour avec succès !";
    if ($_GET['success'] == 'deleted') echo "Thème supprimé avec succès !";
    ?>
            </p>
            <?php endif; ?>

            <!-- Formulaire pour ajouter un thème -->
            <h2 class="mt-5 mb-4 text-center">Ajouter un Thème</h2>

            <form method="POST" action="manage_theme.php" class="w-50 mx-auto bg-light p-4 rounded shadow-sm">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du Thème</label>
                    <input type="text" name="nom" id="nom" class="form-control" placeholder="Nom du thème" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control"
                        placeholder="Description du thème" rows="4" required></textarea>
                </div>
                <button type="submit" name="add_theme" class="btn btn-primary w-100">Ajouter</button>
            </form>

            <?php 
        $pdo = DatabaseConnection::getInstance()->getConnection();

$stmt = $pdo->prepare("SELECT * FROM Themes");
$stmt->execute();
$themes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
            <!-- Liste des thèmes -->
            <h2 class="mt-5 mb-4 text-center">Liste des Thèmes</h2>

            <!-- Tableau des thèmes -->
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Description</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($themes as $theme): ?>
                        <tr>
                            <td><?php echo $theme['id_theme']; ?></td>
                            <td><?php echo htmlspecialchars($theme['nom']); ?></td>
                            <td><?php echo htmlspecialchars($theme['description']); ?></td>
                            <td class="d-flex gap-2">
                                <!-- Formulaire pour modifier un thème -->
                                <form method="POST" action="manage_theme.php"
                                    class="d-inline-flex gap-2 align-items-center">
                                    <input type="hidden" name="id_theme" value="<?php echo $theme['id_theme']; ?>">
                                    <input type="text" name="nom" class="form-control form-control-sm"
                                        value="<?php echo htmlspecialchars($theme['nom']); ?>" required>
                                    <textarea name="description" class="form-control form-control-sm" required
                                        rows="1"><?php echo htmlspecialchars($theme['description']); ?></textarea>
                                    <button type="submit" name="update_theme" class="btn btn-sm btn-warning">
                                        Modifier
                                    </button>
                                </form>

                                <!-- Bouton pour supprimer un thème -->
                                <a href="manage_theme.php?delete=<?php echo $theme['id_theme']; ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce thème ?');">
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>

        <div id="commentaires" class="section-content">
            <div class="container mt-5">
                <h2 class="mb-4">Gestion des Commentaires</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Utilisateur</th>
                                <th>Article</th>
                                <th>Contenu</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                    require_once('../Classes/Commentaire.php');
                    $pdo = DatabaseConnection::getInstance()->getConnection();
                    $query = "SELECT c.*, u.nom as nom_utilisateur, a.titre as titre_article 
                             FROM Commentaires c 
                             JOIN Utilisateurs u ON c.id_utilisateur = u.id_utilisateur 
                             JOIN Articles a ON c.id_article = a.id_article 
                             ORDER BY c.created_at DESC";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute();
                    $commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($commentaires as $commentaire):
                    ?>
                            <tr>
                                <td><?= htmlspecialchars($commentaire['id_commentaire']) ?></td>
                                <td><?= htmlspecialchars($commentaire['nom_utilisateur']) ?></td>
                                <td><?= htmlspecialchars($commentaire['titre_article']) ?></td>
                                <td><?= htmlspecialchars($commentaire['contenu']) ?></td>
                                <td><?= htmlspecialchars($commentaire['created_at']) ?></td>
                                <td>
                                    <form method="POST" action="../manage_commentaire.php" class="d-inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_commentaire"
                                            value="<?= $commentaire['id_commentaire'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="articles" class="section-content">
            <!-- Section des articles  -->
            <div class="container mt-5">
                <h2 class="mb-4">Gestion des Articles</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Titre</th>
                                <th>Thème</th>
                                <th>Date de création</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                require_once('../Classes/Article.php');
                $articles = Article::listerArticles();
                foreach ($articles as $article):
                    $statusClass = match($article['statut']) {
                        'Accepté' => 'bg-success',
                        'Refusé' => 'bg-danger',
                        default => 'bg-warning'
                    };
                ?>
                            <tr>
                                <td><?= htmlspecialchars($article['id_article']) ?></td>
                                <td><?= htmlspecialchars($article['titre']) ?></td>
                                <td><?= htmlspecialchars($article['theme_nom']) ?></td>
                                <td><?= htmlspecialchars($article['date_creation']) ?></td>
                                <td>
                                    <span class="badge <?= $statusClass ?>">
                                        <?= htmlspecialchars($article['statut']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($article['statut'] === 'En attente'): ?>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="article_id" value="<?= $article['id_article'] ?>">
                                        <input type="hidden" name="action_a" value="accept">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Accepter
                                        </button>
                                    </form>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="article_id" value="<?= $article['id_article'] ?>">
                                        <input type="hidden" name="action_a" value="reject">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-times"></i> Refuser
                                        </button>
                                    </form>
                                    <?php else: ?>
                                    <span class="text-muted">Déjà traité</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de la navigation
        const navLinks = document.querySelectorAll('.nav-link');
        const sections = document.querySelectorAll('.section-content');

        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const sectionId = link.getAttribute('data-section');

                // Masquer toutes les sections
                sections.forEach(section => {
                    section.classList.remove('active');
                    section.style.display = 'none';
                });

                // Afficher la section sélectionnée
                const activeSection = document.getElementById(sectionId);
                if (activeSection) {
                    activeSection.classList.add('active');
                    activeSection.style.display = 'block';
                }

                // Mettre à jour les classes active des liens
                navLinks.forEach(navLink => {
                    navLink.classList.remove('active');
                });
                link.classList.add('active');
            });
        });


        // Afficher la première section par défaut (dashboard)
        const defaultSection = document.getElementById('dashboard');
        if (defaultSection) {
            defaultSection.style.display = 'block';
            defaultSection.classList.add('active');
        }
    });
    // Responsive toggle pour la sidebar
    const toggleBtn = document.createElement('button');
    toggleBtn.classList.add('btn', 'btn-primary', 'd-md-none');
    toggleBtn.style.position = 'fixed';
    toggleBtn.style.top = '1rem';
    toggleBtn.style.left = '1rem';
    toggleBtn.style.zIndex = '1001';
    toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
    document.body.appendChild(toggleBtn);

    toggleBtn.addEventListener('click', () => {
        document.querySelector('.sidebar').classList.toggle('active');
        document.querySelector('.main-content').classList.toggle('active');
    });


    document.getElementById('add_car').addEventListener('click', (event) => {
        event.preventDefault(); // Empêche le comportement par défaut (envoi du formulaire)

        const carContainer = document.getElementById('car-container');
        let carCount = carContainer.children.length;

        const newCar = `<div class="car-item">
                        <div class="mb-3">
                            <label for="nomModele${carCount}" class="form-label">Nom du Modèle :</label>
                            <input type="text" class="form-control" name="nomModele${carCount}" id="nomModele${carCount}"
                                placeholder="Entrez le modèle du véhicule" required>
                        </div>

                        <div class="mb-3">
                            <label for="idCategorie${carCount}" class="form-label">Catégorie :</label>
                            <select class="form-select" name="idCategorie${carCount}" id="idCategorie${carCount}" required>
                                <option value="" disabled selected>Choisissez une catégorie</option>
                                <?php
                                    $categories = Categorie::listerCategories();
                                    foreach ($categories as $categorie) {
                                        echo '<option value="' . htmlspecialchars($categorie['id_categorie']) . '">' . htmlspecialchars($categorie['nom_categorie']) . '</option>';
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="prixJournee${carCount}" class="form-label">Prix par Journée :</label>
                            <input type="number" class="form-control" name="prixJournee${carCount}" id="prixJournee${carCount}"
                                step="0.01" placeholder="Entrez le prix par journée" required>
                        </div>

                        <div class="mb-3">
                            <label for="disponibilite${carCount}" class="form-label">Disponibilité :</label>
                            <select class="form-select" name="disponibilite${carCount}" id="disponibilite${carCount}" required>
                                <option value="" disabled selected>Choisissez la disponibilité</option>
                                <option value="Disponible">Disponible</option>
                                <option value="Non disponible">Non disponible</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="imageUrl${carCount}" class="form-label">Image du Véhicule :</label>
                            <input type="file" class="form-control" name="imageUrl${carCount}" id="imageUrl${carCount}"
                                accept="image/*" required>
                        </div>
                    </div>`;

        carContainer.insertAdjacentHTML('beforeend', newCar);
        carCount++;
        document.getElementById('nbr_cars').value = carCount;


    });
    document.addEventListener('DOMContentLoaded', () => {
        const editButtons = document.querySelectorAll('.editVoitureBtn');
        const modal = new bootstrap.Modal(document.getElementById('editVehiculeModal'));

        editButtons.forEach(button => {
            button.addEventListener('click', () => {

                const id = button.getAttribute('data-id');
                const nomModele = button.getAttribute('data-nom-modele');
                const idCategorie = button.getAttribute('data-id-categorie');
                const prixJournee = button.getAttribute('data-prix-journee');
                const disponibilite = button.getAttribute('data-disponibilite');

                document.getElementById('edit_id_vehicule').value = id;
                document.getElementById('edit_nom_modele').value = nomModele;
                document.getElementById('edit_id_categorie').value = idCategorie;
                document.getElementById('edit_prix_journee').value = prixJournee;
                document.getElementById('edit_disponibilite').value = disponibilite;

                modal.show();
            });
        });
    });
    document.addEventListener('DOMContentLoaded', () => {
        const editButtons = document.querySelectorAll('.editAvisBtn');
        const modal = new bootstrap.Modal(document.getElementById('editAvisModal'));

        editButtons.forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const commentaire = button.getAttribute('data-commentaire');
                const evaluation = button.getAttribute('data-evaluation');

                document.getElementById('edit_id_avis').value = id;
                document.getElementById('edit_commentaire').value = commentaire;
                document.getElementById('edit_evaluation').value = evaluation;

                modal.show();
            });
        });
    });
    document.querySelectorAll('.editCategorieBtn').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const nom = button.getAttribute('data-nom');
            const description = button.getAttribute('data-description');

            document.getElementById('editCategorieId').value = id;
            document.getElementById('editNomCategorie').value = nom;
            document.getElementById('editDescriptionCategorie').value = description;

            const editModal = new bootstrap.Modal(document.getElementById('editCategorieModal'));
            editModal.show();
        });
    });
    </script>
</body>

</html>