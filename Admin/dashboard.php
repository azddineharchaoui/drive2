<?php 
   session_start();
if (!isset($_SESSION['user_id']) || (isset($_SESSION['user_id']) && $_SESSION['role_id'] != 1)) {
    header("Location: ../index.php");
    exit;
}
require_once('../Classes/Categorie.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('../Classes/Reservation.php');
    
    if (isset($_POST['action']) && isset($_POST['reservation_id'])) {
        $reservationId = $_POST['reservation_id'];
        $reservation = new Reservation();
        
        if ($_POST['action'] === 'accept') {
            if ($reservation->confirmerReservation($reservationId)) {
                $_SESSION['message'] = "Réservation acceptée avec succès.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Erreur lors de l'acceptation de la réservation.";
                $_SESSION['message_type'] = "danger";
            }
        } elseif ($_POST['action'] === 'reject') {
            if ($reservation->annulerReservation($reservationId)) {
                $_SESSION['message'] = "Réservation refusée avec succès.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Erreur lors du refus de la réservation.";
                $_SESSION['message_type'] = "danger";
            }
        }
        
        header('Location: ' . $_SERVER['PHP_SELF'] . '#reservations');
        exit();
    }
}

if (isset($_SESSION['message'])): ?>
<div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
    <?= $_SESSION['message'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php 
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
endif;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    .custom-btn {
        background-color: #ce1212;
        color: #fff;
    }
    </style>
</head>

<body>
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#reservations">Reservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#voitures">Voitures</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#avis">Avis</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#categorie">Categories</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <main class="col-md-12 ms-sm-auto col-lg-12 px-md-4 mt-10">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom ">
                    <h1 class="h2">Dashboard</h1>
                </div>

                <!-- Reservations Table -->
                <div class="card mb-4" id="reservations">
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

                <!-- Manage Vehicules Section -->
                <div class="card mb-4" id="vehicules">
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
    </div>
    <!-- Modal Modifier Vehicule -->
    <!-- Modal pour la modification d'un véhicule -->
    <div class="modal fade" id="editVehiculeModal" tabindex="-1" aria-labelledby="editVehiculeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editVehiculeForm" method="POST" action="manage_vehicule.php" enctype="multipart/form-data">
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
                            <input type="text" class="form-control" id="edit_prix_journee" name="prix_journee" required>
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

    <!-- Section des avis -->
    <div class="container mt-5" id="avis">
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


    <div class="modal fade" id="editAvisModal" tabindex="-1" aria-labelledby="editAvisModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editAvisForm" action="manage_avis.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAvisModalLabel">Modifier l'Avis</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="edit_avis" value="1">
                        <input type="hidden" id="edit_id_avis" name="id_avis">
                        <div class="mb-3">
                            <label for="edit_commentaire" class="form-label">Commentaire</label>
                            <textarea class="form-control" id="edit_commentaire" name="commentaire" required></textarea>
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

    <!-- Section de categorie -->

    <div class="container mt-5" id="categorie">
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
            require_once('../Classes/Categorie.php');

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
                            <input type="hidden" name="delete_categorie" value="<?= $categorie['id_categorie']; ?>">
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_categorie" id="editCategorieId">
                        <div class="mb-3">
                            <label for="editNomCategorie" class="form-label">Nom de la Catégorie</label>
                            <input type="text" name="nom_categorie" id="editNomCategorie" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDescriptionCategorie" class="form-label">Description</label>
                            <textarea name="description_categorie" id="editDescriptionCategorie" class="form-control"
                                rows="3"></textarea>
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


    </main>
    </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
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
                                    require_once('../Classes/Categorie.php');
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