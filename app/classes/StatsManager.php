<?php
class StatsManager {
    // On stocke les données dans un fichier .json au lieu d'un serveur
    private $jsonFile = __DIR__ . '/../logs/stats.json';

    public function __construct() {
        // Création du dossier et du fichier si inexistants
        if (!is_dir(__DIR__ . '/../logs')) { mkdir(__DIR__ . '/../logs'); }
        if (!file_exists($this->jsonFile)) { file_put_contents($this->jsonFile, json_encode([])); }
    }

    // Enregistrer une commande (Comme un "INSERT" NoSQL)
    public function recordOrder($menuTitre, $prix) {
        // 1. Lire les données actuelles
        $jsonContent = file_get_contents($this->jsonFile);
        $data = json_decode($jsonContent, true) ?? [];
        
        // 2. Créer le "Document" (Structure NoSQL)
        $document = [
            '_id' => uniqid(),
            'date' => date('Y-m-d H:i:s'),
            'menu' => $menuTitre,
            'prix' => (float)$prix
        ];

        // 3. Ajouter et Sauvegarder
        $data[] = $document;
        file_put_contents($this->jsonFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    // Récupérer les stats (Comme un "FIND" + "AGGREGATE")
    public function getStatsForChart() {
        $jsonContent = file_get_contents($this->jsonFile);
        $data = json_decode($jsonContent, true) ?? [];
        
        $counts = [];
        $ca_total = 0;

        foreach ($data as $entry) {
            $menu = $entry['menu'];
            $prix = $entry['prix'];
            
            // Comptage par menu
            if (!isset($counts[$menu])) { $counts[$menu] = 0; }
            $counts[$menu]++;
            
            // Somme du CA
            $ca_total += $prix;
        }

        return [
            'labels' => array_keys($counts),
            'data' => array_values($counts),
            'total_ca' => $ca_total
        ];
    }
}
?>