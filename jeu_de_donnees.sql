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