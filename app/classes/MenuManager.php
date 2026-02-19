<?php
class MenuManager {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db->getPdo();
    }

    

    public function getAllMenus() {
        return $this->db->query("SELECT * FROM menus WHERE est_visible = 1 ORDER BY prix ASC")->fetchAll();
    }

    public function getMenuById($id) {
        $stmt = $this->db->prepare("SELECT * FROM menus WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getMenuComposition($menuId) {
        $sql = "SELECT p.nom, p.type, p.allergenes 
                FROM plats p
                JOIN menu_composition mc ON p.id = mc.plat_id
                WHERE mc.menu_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$menuId]);
        return $stmt->fetchAll();
    }
    
    public function formatComposition($plats) {
        $comp = ["Entrée" => "Aucune", "Plat" => "Aucun", "Dessert" => "Aucun"];
        $allergenes = [];
        
        foreach ($plats as $p) {
            if ($p['type'] == 'entree') $comp['Entrée'] = $p['nom'];
            if ($p['type'] == 'plat') $comp['Plat'] = $p['nom'];
            if ($p['type'] == 'dessert') $comp['Dessert'] = $p['nom'];
            
            if (!empty($p['allergenes']) && $p['allergenes'] !== 'Aucun') {
                $allergenes = array_merge($allergenes, array_map('trim', explode(',', $p['allergenes'])));
            }
        }
        return ['plats' => $comp, 'allergenes' => implode(", ", array_unique($allergenes))];
    }

    

    public function addMenu($data, $file) {
        try {
            
            $this->db->beginTransaction();

           
            $targetDir = "assets/img/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true); // Créer dossier si existe pas
            
            $fileName = uniqid() . '_' . basename($file["name"]); // Nom unique pour éviter conflits
            $targetFilePath = $targetDir . $fileName;
            
            if (!move_uploaded_file($file["tmp_name"], $targetFilePath)) {
                throw new Exception("Erreur upload image");
            }

           
            $sqlMenu = "INSERT INTO menus (titre, description, prix, min_pers, theme, regime, photo, est_visible) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
            $stmt = $this->db->prepare($sqlMenu);
            $stmt->execute([
                $data['titre'], 
                $data['description'], 
                $data['prix'], 
                $data['min_pers'], 
                $data['theme'], 
                $data['regime'], 
                $targetFilePath
            ]);
            
            
            $menuId = $this->db->lastInsertId();

            
            $sqlPlat = "INSERT INTO plats (nom, type, allergenes) VALUES (?, ?, ?)";
            $stmtPlat = $this->db->prepare($sqlPlat);

            $sqlLiaison = "INSERT INTO menu_composition (menu_id, plat_id) VALUES (?, ?)";
            $stmtLiaison = $this->db->prepare($sqlLiaison);

            
            if (!empty($data['entree'])) {
                $stmtPlat->execute([$data['entree'], 'entree', $data['allergenes_entree'] ?? 'Aucun']);
                $platId = $this->db->lastInsertId();
                $stmtLiaison->execute([$menuId, $platId]);
            }

            
            if (!empty($data['plat'])) {
                $stmtPlat->execute([$data['plat'], 'plat', $data['allergenes_plat'] ?? 'Aucun']);
                $platId = $this->db->lastInsertId();
                $stmtLiaison->execute([$menuId, $platId]);
            }

            
            if (!empty($data['dessert'])) {
                $stmtPlat->execute([$data['dessert'], 'dessert', $data['allergenes_dessert'] ?? 'Aucun']);
                $platId = $this->db->lastInsertId();
                $stmtLiaison->execute([$menuId, $platId]);
            }

            
            $this->db->commit();
            return true;

        } catch (Exception $e) {
            
            $this->db->rollBack();
            return false; 
        }
    }

    public function deleteMenu($id) {
       
        return $this->db->prepare("DELETE FROM menus WHERE id = ?")->execute([$id]);
    }
}