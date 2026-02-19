<?php
class ReviewManager {
    private $db;
    public function __construct(Database $db) { $this->db = $db->getPdo(); }

    // Récupérer les avis en attente (Admin)
    public function getPending() {
        return $this->db->query("SELECT * FROM avis WHERE statut = 'en_attente'")->fetchAll();
    }

    // Valider un avis
    public function validate($id) {
        return $this->db->prepare("UPDATE avis SET statut = 'valide' WHERE id = ?")->execute([$id]);
    }

    // Refuser un avis
    public function refuse($id) {
        return $this->db->prepare("UPDATE avis SET statut = 'refuse' WHERE id = ?")->execute([$id]);
    }

    
    public function getReviewsByMenu($menuId) {
        // Jointure pour avoir le prénom de l'utilisateur qui a laissé l'avis
        $sql = "SELECT a.*, u.prenom 
                FROM avis a
                JOIN commandes c ON a.commande_id = c.id
                JOIN users u ON c.user_id = u.id
                WHERE c.menu_id = ? AND a.statut = 'valide'
                ORDER BY a.date_avis DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$menuId]);
        return $stmt->fetchAll();
    }
}
?>