<?php 
    require 'config/init.php';
    if (!isset($_SESSION['role']) || $_SESSION['role'] == 'client') { header("Location: index.php"); exit(); }

    $reviewManager = new ReviewManager($db);
    if (isset($_GET['valider'])) $reviewManager->validate($_GET['valider']);
    if (isset($_GET['refuser'])) $reviewManager->refuse($_GET['refuser']);

    $avis_pendants = $reviewManager->getPending();
    $titre = "Modération Avis";
    include 'header.php';
?>

<div class="admin-layout">
    <aside class="sidebar">
        <?php include 'includes/sidebar-admin.php'; ?>
    </aside>

    <main class="admin-content">
        <h1>Modération des Avis</h1>
        
        <div class="table-conteneur">
            <table class="data-table">
                <thead><tr><th>Date</th><th>Note</th><th>Commentaire</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach($avis_pendants as $avis): ?>
                        <tr>
                            <td><?php echo $avis['date_avis']; ?></td>
                            <td><?php echo $avis['note']; ?>/5</td>
                            <td><?php echo htmlspecialchars($avis['commentaire']); ?></td>
                            <td class="actions">
                                <a href="?valider=<?php echo $avis['id']; ?>" class="btn-icon success"><i class="fas fa-check"></i></a>
                                <a href="?refuser=<?php echo $avis['id']; ?>" class="btn-icon delete"><i class="fas fa-times"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if(empty($avis_pendants)) echo "<p>Aucun avis en attente.</p>"; ?>
        </div>
    </main>
</div>