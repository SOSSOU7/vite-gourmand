<?php
class OrderManager {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db->getPdo();
    }

   
    public function calculateTotal($prixUnitaire, $minPers, $nbPers, $distance) {
        
        if ($nbPers < $minPers) $nbPers = $minPers;

       
        $sousTotal = $nbPers * $prixUnitaire;

       
        $livraison = 5.00 + ($distance * 0.59);

        // Réduction (-10% si +5 pers)
        $reduction = 0;
        if ($nbPers >= ($minPers + 5)) {
            $reduction = $sousTotal * 0.10;
        }

        return $sousTotal + $livraison - $reduction;
    }

    // Créer une commande
    public function createOrder($userId, $menuId, $data, $prixTotal) {
        $sql = "INSERT INTO commandes (user_id, menu_id, date_livraison, heure_livraison, adresse_livraison, distance_km, nb_pers, prix_total, statut) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'en_attente')";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $userId, $menuId, $data['date'], $data['heure'], 
            $data['adresse'], $data['distance'], $data['nb_pers'], $prixTotal
        ]);
    }

    // Historique utilisateur
    public function getUserHistory($userId) {
        $sql = "SELECT c.*, m.titre as menu_titre 
                FROM commandes c 
                JOIN menus m ON c.menu_id = m.id
                WHERE c.user_id = ? ORDER BY c.date_commande DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Une commande spécifique
    public function getOrder($id, $userId) {
        $sql = "SELECT c.*, m.titre as menu_titre 
                FROM commandes c
                JOIN menus m ON c.menu_id = m.id
                WHERE c.id = ? AND c.user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }
    
    // Annuler commande
    public function cancelOrder($id) {
        $sql = "UPDATE commandes SET statut = 'annulee' WHERE id = ?";
        return $this->db->prepare($sql)->execute([$id]);
    }
    public function updateStatus($id, $newStatus) {
        $sql = "UPDATE commandes SET statut = ? WHERE id = ?";
        return $this->db->prepare($sql)->execute([$newStatus, $id]);
    }
}