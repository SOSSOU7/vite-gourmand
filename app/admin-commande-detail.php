<?php 
    require 'config/init.php';
    // Sécurité : Admin ou Employé
    if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'employe')) { header("Location: index.php"); exit(); }

    $orderManager = new OrderManager($db);
    $mailManager = new MailManager(); // Pour notifier le client

    $id = $_GET['id'] ?? 0;
    
   
    $sql = "SELECT c.*, m.titre as menu_titre, u.nom, u.prenom, u.email 
            FROM commandes c
            JOIN menus m ON c.menu_id = m.id
            JOIN users u ON c.user_id = u.id
            WHERE c.id = ?";
    $stmt = $db->getPdo()->prepare($sql);
    $stmt->execute([$id]);
    $commande = $stmt->fetch();

    if (!$commande) die("Commande introuvable");

    // TRAITEMENT CHANGEMENT STATUT
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_statut'])) {
        $newStatut = $_POST['new_statut'];
        $orderManager->updateStatus($id, $newStatut);
        
        // Notification Mail selon le statut
        if ($newStatut == 'retour_materiel') {
            $mailManager->send($commande['email'], "Retour Matériel - Urgent", "Merci de restituer le matériel sous 10 jours sinon pénalité de 600€.");
        } elseif ($newStatut == 'terminee') {
            $mailManager->send($commande['email'], "Commande Terminée", "Votre commande est terminée. Connectez-vous pour donner votre avis !");
        }

        header("Location: admin-commande-detail.php?id=$id&msg=updated");
        exit();
    }

    $titre = "Détail Commande #$id";
    include 'header.php';
?>

<div class="admin-layout">
    <aside class="sidebar">
        <?php include 'includes/sidebar-admin.php'; ?>
    </aside>

    <main class="admin-content">
        <div class="content-header">
            <h1>Commande #<?php echo $commande['id']; ?></h1>
            <a href="admin-dashboard.php" class="btn-secondary">Retour tableau</a>
        </div>

        <?php if(isset($_GET['msg'])) echo "<div class='alert success'>Statut mis à jour !</div>"; ?>

        <div class="stats-grid"> <div class="card-box">
                <h3>Client</h3>
                <p><strong>Nom :</strong> <?php echo htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']); ?></p>
                <p><strong>Email :</strong> <?php echo htmlspecialchars($commande['email']); ?></p>
                <p><strong>Adresse Livraison :</strong><br><?php echo nl2br(htmlspecialchars($commande['adresse_livraison'])); ?></p>
            </div>

            <div class="card-box">
                <h3>Détails</h3>
                <p><strong>Menu :</strong> <?php echo htmlspecialchars($commande['menu_titre']); ?></p>
                <p><strong>Date :</strong> <?php echo date("d/m/Y", strtotime($commande['date_livraison'])); ?> à <?php echo $commande['heure_livraison']; ?></p>
                <p><strong>Invités :</strong> <?php echo $commande['nb_pers']; ?> pers.</p>
                <p style="font-size: 1.2rem; margin-top:10px;">Total : <strong><?php echo $commande['prix_total']; ?> €</strong></p>
            </div>

            <div class="card-box">
                <h3>Gestion du Statut</h3>
                <form method="POST">
                    <div class="input-group">
                        <label>Statut Actuel</label>
                        <select name="new_statut" class="form-select" style="padding: 10px; width: 100%;">
                            <?php 
                                $statuts = [
                                    'en_attente' => 'En attente',
                                    'accepte' => 'Accepté (Validé)',
                                    'preparation' => 'En cuisine',
                                    'livraison' => 'En livraison',
                                    'livre' => 'Livré (Chez le client)',
                                    'retour_materiel' => 'Attente retour matériel',
                                    'terminee' => 'Terminée (Clôturée)',
                                    'annulee' => 'Annulée'
                                ];
                                foreach($statuts as $key => $label): 
                            ?>
                                <option value="<?php echo $key; ?>" <?php echo ($commande['statut'] == $key) ? 'selected' : ''; ?>>
                                    <?php echo $label; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary width-100" style="margin-top: 15px;">Mettre à jour le statut</button>
                </form>
                
                <?php if($commande['statut'] == 'retour_materiel'): ?>
                    <div class="alert warning" style="margin-top: 10px; font-size: 0.8rem;">
                        <i class="fas fa-exclamation-triangle"></i> Attention : Le client a du matériel à rendre.
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </main>
</div>