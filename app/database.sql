DROP DATABASE IF EXISTS vite_gourmand;
CREATE DATABASE vite_gourmand CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE vite_gourmand;
 

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Sera haché
    tel VARCHAR(20),
    adresse TEXT,
    role ENUM('client', 'employe', 'admin') DEFAULT 'client',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
 

CREATE TABLE menus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(100) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    min_pers INT DEFAULT 4,
    stock INT DEFAULT 0,
    theme VARCHAR(50), -- Ex: Noël, Pâques...
    regime VARCHAR(50), -- Ex: Végétarien...
    photo VARCHAR(255),
    delai_commande VARCHAR(100) DEFAULT '48h à l''avance',
    est_visible BOOLEAN DEFAULT TRUE
);
 

CREATE TABLE plats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    type ENUM('entree', 'plat', 'dessert') NOT NULL,
    allergenes VARCHAR(255) -- Ex: Gluten, Arachides
);
 

CREATE TABLE menu_composition (
    menu_id INT,
    plat_id INT,
    PRIMARY KEY(menu_id, plat_id),
    FOREIGN KEY(menu_id) REFERENCES menus(id) ON DELETE CASCADE,
    FOREIGN KEY(plat_id) REFERENCES plats(id) ON DELETE CASCADE
);
 

CREATE TABLE commandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    menu_id INT NOT NULL,
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_livraison DATE NOT NULL,
    heure_livraison TIME NOT NULL,
    adresse_livraison TEXT NOT NULL,
    distance_km INT DEFAULT 0,
    nb_pers INT NOT NULL,
    prix_total DECIMAL(10,2) NOT NULL,
    statut ENUM('en_attente', 'accepte', 'preparation', 'livraison', 'livre', 'retour_materiel', 'terminee', 'annulee') DEFAULT 'en_attente',
    materiel_prete BOOLEAN DEFAULT FALSE, -- Pour gérer la pénalité de 600€
    FOREIGN KEY(user_id) REFERENCES users(id),
    FOREIGN KEY(menu_id) REFERENCES menus(id)
);
 

CREATE TABLE avis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    note INT CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    statut ENUM('en_attente', 'valide', 'refuse') DEFAULT 'en_attente',
    date_avis DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(commande_id) REFERENCES commandes(id)
);
 
 
 

INSERT INTO users (nom, prenom, email, password, tel, adresse, role) VALUES 
('Gourmand', 'Julie', 'admin@fastdev.fr', '$2y$10$M/..ExempleHachéPourAdmin123!.....', '0600000000', 'Bordeaux Centre', 'admin'),
('Martin', 'Sophie', 'client@mail.com', '$2y$10$M/..ExempleHachéPourClient123!....', '0612345678', '12 rue du Port, Bordeaux', 'client'),
('Dupond', 'Marc', 'employe@vite-gourmand.fr', '$2y$10$M/..ExempleHachéPourEmploye123!....', '0699887766', 'Bordeaux', 'employe');

 
INSERT INTO plats (nom, type, allergenes) VALUES 
('Salade Truffe', 'entree', 'Aucun'),
('Rôti Végétal', 'plat', 'Soja'),
('Mousse Chocolat', 'dessert', 'Lait, Oeufs'),
('Foie Gras Maison', 'entree', 'Aucun'),
('Dinde aux Marrons', 'plat', 'Aucun'),
('Bûche Glacée', 'dessert', 'Lait, Gluten');
 
INSERT INTO menus (titre, description, prix, min_pers, stock, theme, regime, photo) VALUES 
('Menu Classique de Noël', 'Un menu raffiné revisitant les classiques.', 20.00, 4, 50, 'Noël', 'Végétarien', 'assets/img/plat1.jpg'),
('Menu Prestige', 'Le luxe pour vos fêtes.', 45.00, 8, 20, 'Noël', 'Classique', 'assets/img/plat2.jpg'),
('Buffet Champêtre', 'Idéal pour les beaux jours.', 25.00, 15, 10, 'Évènement', 'Classique', 'assets/img/plat3.jpg');
 

INSERT INTO menu_composition VALUES (1, 1), (1, 2), (1, 3);
INSERT INTO menu_composition VALUES (2, 4), (2, 5), (2, 6);
 
INSERT INTO commandes (user_id, menu_id, date_livraison, heure_livraison, adresse_livraison, nb_pers, prix_total, statut) VALUES 
(2, 1, '2023-12-24', '19:00:00', '12 rue du Port, Bordeaux', 6, 125.00, 'terminee'),
(2, 2, '2023-12-31', '20:00:00', '12 rue du Port, Bordeaux', 10, 450.00, 'en_attente');
 
INSERT INTO avis (commande_id, note, commentaire, statut) VALUES 
(1, 5, 'C''était délicieux, merci !', 'valide');