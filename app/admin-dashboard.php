<?php 
    require 'config/init.php';

    // Sécurité : Admin ou Employé uniquement
    if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'employe')) {
        header("Location: index.php"); exit();
    }

    $titre = "Tableau de bord";
    include 'header.php'; 

    // Récupération commandes
    $sql = "SELECT c.*, u.nom, u.prenom FROM commandes c JOIN users u ON c.user_id = u.id ORDER BY c.date_livraison ASC";
    $commandes = $db->getPdo()->query($sql)->fetchAll();
?>

<div class="admin-layout">
    <aside class="sidebar">
        <?php include 'includes/sidebar-admin.php'; ?>
    </aside>

    <main class="admin-content">
        <div class="content-header">
            <h1>Tableau de bord</h1>
        </div>
        
        <div class="table-conteneur">
            <h2>Commandes en cours</h2>
            <table class="data-table">
                <thead>
                    <tr><th>N°</th><th>Client</th><th>Date</th><th>Montant</th><th>Statut</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach($commandes as $cmd): ?>
                        <tr>
                            <td>#<?php echo $cmd['id']; ?></td>
                            <td><?php echo htmlspecialchars($cmd['prenom'].' '.$cmd['nom']); ?></td>
                            <td><?php echo date("d/m/Y", strtotime($cmd['date_livraison'])); ?></td>
                            <td><strong><?php echo $cmd['prix_total']; ?>€</strong></td>
                            <td><span class="badge <?php echo ($cmd['statut']=='livré')?'success':'warning'; ?>"><?php echo $cmd['statut']; ?></span></td>
                            <td><a href="admin-commande-detail.php?id=<?php echo $cmd['id']; ?>" class="btn-icon"><i class="fas fa-eye"></i></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>