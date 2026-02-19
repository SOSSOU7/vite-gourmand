
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