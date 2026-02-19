<?php
    
    $current_page = basename($_SERVER['PHP_SELF']);
    $role = $_SESSION['role'] ?? '';
    $prenom = $_SESSION['prenom'] ?? 'Utilisateur';
?>

<div class="sidebar-header">
    <div class="logo">VITE & <span class="text-orange">GOURMAND</span></div>
    <div class="user-info">
        <p>Bonjour, <strong><?php echo htmlspecialchars($prenom); ?></strong></p>
        <span class="badge"><?php echo ucfirst($role); ?></span>
    </div>
</div>

<nav class="sidebar-nav">
    <p class="nav-title">GESTION</p>
    
    <a href="admin-dashboard.php" class="<?php echo ($current_page == 'admin-dashboard.php') ? 'active' : ''; ?>">
        <i class="fas fa-clipboard-list"></i> <span>Commandes</span>
    </a>

    <a href="admin-menu-form.php" class="<?php echo ($current_page == 'admin-menu-form.php') ? 'active' : ''; ?>">
        <i class="fas fa-utensils"></i> <span>Nouveau Menu</span>
    </a>

    <a href="admin-avis.php" class="<?php echo ($current_page == 'admin-avis.php') ? 'active' : ''; ?>">
        <i class="fas fa-star"></i> <span>Avis Clients</span>
    </a>

    <?php if($role === 'admin'): ?>
        <p class="nav-title">ADMINISTRATION</p>
        
        <a href="admin-users.php" class="<?php echo ($current_page == 'admin-users.php') ? 'active' : ''; ?>">
            <i class="fas fa-users-cog"></i> <span>Utilisateurs</span>
        </a>

        <a href="admin-stats.php" class="<?php echo ($current_page == 'admin-stats.php') ? 'active' : ''; ?>">
            <i class="fas fa-chart-line"></i> <span>Statistiques</span>
        </a>
    <?php endif; ?>

    <p class="nav-title">NAVIGATION</p>
    <a href="index.php"><i class="fas fa-home"></i> <span>Voir le site</span></a>
    <a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span></a>
</nav>