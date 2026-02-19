<?php 
    require 'config/init.php';

    if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

    $orderManager = new OrderManager($db);
    $id_cmd = $_GET['id'] ?? 0;

    
    $commande = $orderManager->getOrder($id_cmd, $_SESSION['user_id']);

    if (!$commande) {
        die("Commande introuvable.");
    }

    // Gestion Annulation via l'objet
    if (isset($_POST['annuler']) && $commande['statut'] === 'en_attente') {
        $orderManager->cancelOrder($id_cmd);
        header("Location: suivi-commande.php?id=$id_cmd");
        exit();
    }

    $is_annulable = ($commande['statut'] === "en_attente");
    $titre = "Suivi Commande #$id_cmd";
    include 'header.php'; 
?>

<main class="conteneur tracking-page">
    <a href="profil.php" class="back-link"><i class="fas fa-arrow-left"></i> Retour</a>

    <div class="tracking-header">
        <h1>Commande #<?php echo $commande['id']; ?></h1>
        <span class="badge-status big <?php echo strtolower($commande['statut']); ?>">
            <?php echo str_replace('_', ' ', strtoupper($commande['statut'])); ?>
        </span>
    </div>

    <div class="tracking-grid">
        <div class="card-box timeline-box">
            <h3>État d'avancement</h3>
            <ul class="timeline">
                <li class="active">
                    <span class="text">Commande reçue</span>
                    <span class="time"><?php echo date("d/m H:i", strtotime($commande['date_commande'])); ?></span>
                </li>
                <?php if($commande['statut'] == 'annulee'): ?>
                    <li class="active" style="color:red;"><span class="text">Annulée</span></li>
                <?php else: ?>
                    <li class="<?php echo in_array($commande['statut'], ['preparation', 'livraison', 'terminee']) ? 'active' : ''; ?>">
                        <span class="text">Préparation</span>
                    </li>
                    <li class="<?php echo in_array($commande['statut'], ['livraison', 'terminee']) ? 'active' : ''; ?>">
                        <span class="text">Livraison</span>
                    </li>
                    <li class="<?php echo ($commande['statut'] == 'terminee') ? 'active' : ''; ?>">
                        <span class="text">Terminée</span>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="card-box details-box">
            <h3>Détails</h3>
            <p><strong>Menu :</strong> <?php echo htmlspecialchars($commande['menu_titre']); ?></p>
            <p><strong>Date livraison :</strong> <?php echo date("d/m/Y", strtotime($commande['date_livraison'])); ?></p>
            <p><strong>Adresse :</strong> <?php echo htmlspecialchars($commande['adresse_livraison']); ?></p>
            <p class="total-price">Total : <?php echo $commande['prix_total']; ?>€</p>
            
            <hr>
            <?php if($is_annulable): ?>
                <div class="alert warning">Annulation possible tant que la commande est en attente.</div>
                <form method="POST">
                    <button type="submit" name="annuler" class="btn-danger width-100" onclick="return confirm('Sûr ?');">Annuler</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>