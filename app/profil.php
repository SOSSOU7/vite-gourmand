<?php 
    require 'config/init.php';

    if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

    $userManager = new UserManager($db);
    $orderManager = new OrderManager($db);

    // 1. Mise à jour profil
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userManager->updateProfile(
            $_SESSION['user_id'],
            htmlspecialchars($_POST['nom']),
            htmlspecialchars($_POST['prenom']),
            htmlspecialchars($_POST['tel']),
            htmlspecialchars($_POST['adresse'])
        );
        header("Location: profil.php?updated=1");
        exit();
    }

    // 2. Récupération des données
    $user = $userManager->getUserById($_SESSION['user_id']);
    $historique = $orderManager->getUserHistory($_SESSION['user_id']);

    $titre = "Mon Espace - Vite & Gourmand";
    include 'header.php'; 
?>
<main class="profil-page conteneur">
    <h1>Mon Espace Client</h1>
    
    <?php if(isset($_GET['succes']) && $_GET['succes'] == 'commande_ok'): ?>
        <div class="alert success">Merci ! Votre commande a été enregistrée avec succès.</div>
    <?php endif; ?>
    <?php if(isset($_GET['updated'])): ?>
        <div class="alert success">Profil mis à jour.</div>
    <?php endif; ?>
    <?php if(isset($_GET['avis_sent'])): ?>
        <div class="alert success">Merci pour votre avis ! Il sera visible après validation.</div>
    <?php endif; ?>

    <div class="profil-grid">
        <section class="card-box">
            <h2>Mes Informations</h2>
            <form action="" method="POST" class="profil-form">
                <div class="input-group">
                    <label>Nom</label>
                    <input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>">
                </div>
                <div class="input-group">
                    <label>Prénom</label>
                    <input type="text" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>">
                </div>
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled style="background:#f0f0f0;">
                </div>
                <div class="input-group">
                    <label>Téléphone</label>
                    <input type="tel" name="tel" value="<?php echo htmlspecialchars($user['tel']); ?>">
                </div>
                <div class="input-group">
                    <label>Adresse par défaut</label>
                    <textarea name="adresse"><?php echo htmlspecialchars($user['adresse']); ?></textarea>
                </div>
                <button type="submit" class="btn-secondary">Mettre à jour</button>
            </form>
        </section>

        <section class="card-box">
            <h2>Mes Commandes</h2>
            <?php if(count($historique) > 0): ?>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Date Livraison</th>
                        <th>Menu</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($historique as $cmd): ?>
                        <tr>
                            <td>#<?php echo $cmd['id']; ?></td>
                            <td><?php echo date("d/m/Y", strtotime($cmd['date_livraison'])); ?></td>
                            <td><?php echo htmlspecialchars($cmd['menu_titre']); ?></td>
                            <td><?php echo $cmd['prix_total']; ?>€</td>
                            <td>
                                <span class="badge-status <?php echo strtolower($cmd['statut']); ?>">
                                    <?php echo str_replace('_', ' ', $cmd['statut']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="suivi-commande.php?id=<?php echo $cmd['id']; ?>" class="btn-small">Voir</a>
                                
                                <?php if($cmd['statut'] === "terminee"): ?>
                                    <a href="donner-avis.php?id=<?php echo $cmd['id']; ?>" class="btn-small btn-star" title="Donner mon avis" style="margin-left:5px;">
                                        <i class="fas fa-star"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>Aucune commande.</p>
                <a href="menu.php" class="btn-primary" style="margin-top:10px; display:inline-block;">Commander</a>
            <?php endif; ?>
        </section>
    </div>
</main>
<?php include 'footer.php'; ?>