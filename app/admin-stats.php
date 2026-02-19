<?php 
    require 'config/init.php';
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: index.php"); exit(); }

    require_once 'classes/StatsManager.php';
    $statsManager = new StatsManager();
    $stats = $statsManager->getStatsForChart();
    
    $titre = "Statistiques";
    include 'header.php';
?>

<div class="admin-layout">
    <aside class="sidebar">
        <?php include 'includes/sidebar-admin.php'; ?>
    </aside>

    <main class="admin-content">
        <h1>Statistiques des Ventes</h1>
        
        <div class="kpi-grid">
            <div class="kpi-card">
                <strong><?php echo number_format($stats['total_ca'], 2); ?> €</strong>
                <span>Chiffre d'Affaires Total</span>
            </div>
        </div>

        <div class="card-box" style="margin-top: 30px;">
            <h3>Répartition des commandes par Menu</h3>
            <canvas id="ordersChart" style="max-height: 400px;"></canvas>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('ordersChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($stats['labels']); ?>,
            datasets: [{
                label: 'Nombre de commandes',
                data: <?php echo json_encode($stats['data']); ?>,
                backgroundColor: '#E65100',
                borderColor: '#E65100',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
</script>