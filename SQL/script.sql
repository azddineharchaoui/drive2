CREATE DATABASE IF NOT EXISTS Drive;
USE Drive;


CREATE TABLE IF NOT EXISTS roles (
    id_role INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nom_role ENUM('admin', 'client')
);

CREATE TABLE IF NOT EXISTS Utilisateurs (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    id_role INT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_role) REFERENCES roles(id_role) ON DELETE CASCADE 
);

CREATE TABLE IF NOT EXISTS Categories (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(50) NOT NULL,
    description TEXT
);

CREATE TABLE IF NOT EXISTS Vehicules (
    id_vehicule INT AUTO_INCREMENT PRIMARY KEY,
    nom_modele VARCHAR(100) NOT NULL,
    id_categorie INT NOT NULL,
    prix_journee DECIMAL(10, 2) NOT NULL,
    disponibilite ENUM('Disponible', 'Non disponible'),
    image_url MEDIUMBLOB,
    FOREIGN KEY (id_categorie) REFERENCES Categories(id_categorie) ON DELETE CASCADE 
);

CREATE TABLE IF NOT EXISTS Reservations (
    id_reservation INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    id_vehicule INT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    lieu_depart VARCHAR(100),
    lieu_retour VARCHAR(100),
    statut ENUM('en attente', 'confirmée', 'annulée') DEFAULT 'en attente',
    date_reservation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES Utilisateurs(id_utilisateur) ON DELETE CASCADE,
    FOREIGN KEY (id_vehicule) REFERENCES Vehicules(id_vehicule) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS Avis (
    id_avis INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    id_vehicule INT NOT NULL,
    commentaire TEXT,
    evaluation INT CHECK (evaluation BETWEEN 1 AND 5),
    actif BOOLEAN DEFAULT TRUE, 
    FOREIGN KEY (id_utilisateur) REFERENCES Utilisateurs(id_utilisateur) ON DELETE CASCADE ,
    FOREIGN KEY (id_vehicule) REFERENCES Vehicules(id_vehicule) ON DELETE CASCADE
);

CREATE OR REPLACE VIEW ListeVehicules AS
SELECT 
    v.id_vehicule,
    v.nom_modele,
    c.nom_categorie,
    v.prix_journee,
    v.disponibilite,
    v.image_url,
    AVG(a.evaluation) AS evaluation_moyenne
FROM Vehicules v
LEFT JOIN Categories c ON v.id_categorie = c.id_categorie
LEFT JOIN Avis a ON v.id_vehicule = a.id_vehicule AND a.actif = TRUE
GROUP BY v.id_vehicule;

DELIMITER //
CREATE PROCEDURE AjouterReservation(
    IN p_id_utilisateur INT,
    IN p_id_vehicule INT,
    IN p_date_debut DATE,
    IN p_date_fin DATE,
    IN p_lieu_depart VARCHAR(100),
    IN p_lieu_retour VARCHAR(100)
)
BEGIN
    DECLARE disponibilite BOOLEAN;
    
    SELECT disponibilite INTO disponibilite
    FROM Vehicules
    WHERE id_vehicule = p_id_vehicule;
    
    IF disponibilite = TRUE THEN

        INSERT INTO Reservations (id_utilisateur, id_vehicule, date_debut, date_fin, lieu_depart, lieu_retour)
        VALUES (p_id_utilisateur, p_id_vehicule, p_date_debut, p_date_fin, p_lieu_depart, p_lieu_retour);
        
        UPDATE Vehicules
        SET disponibilite = FALSE
        WHERE id_vehicule = p_id_vehicule;
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le véhicule n\'est pas disponible.';
    END IF;
END //
DELIMITER ;

SET GLOBAL max_allowed_packet=103741824;
