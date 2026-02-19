<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($titre) ? $titre : "Vite & Gourmand"; ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="en-tete-principal">
        <nav class="barre-nav conteneur">
            <div class="logo">VITE & <span class="text-orange">GOURMAND</span></div>
            
            <div class="menu-burger" id="mobile-menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>

            <ul class="liens-nav">
    <li><a href="index.php" class="element-nav <?php echo ($nav === 'accueil') ? 'active' : ''; ?>">ACCUEIL</a></li>
    <li><a href="menu.php" class="element-nav <?php echo ($nav === 'menu') ? 'active' : ''; ?>">MENU</a></li>
    <li><a href="contact.php" class="element-nav <?php echo ($nav === 'contact') ? 'active' : ''; ?>">Contactez-nous</a></li>

    <?php if (isset($_SESSION['user_id'])): ?>
        <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'employe')): ?>
            <li>
                <a href="admin-dashboard.php" class="btn-login" style="background-color: #333; border-color: #333; color: white;">
                    <i class="fas fa-tachometer-alt"></i> DASHBOARD
                </a>
            </li>
        <?php else: ?>
            <li>
                <a href="profil.php" class="btn-login">
                    <i class="fas fa-user"></i> MON COMPTE
                </a>
            </li>
        <?php endif; ?>

        <li>
            <a href="logout.php" class="element-nav" title="Se déconnecter" style="color: #e74c3c;">
                <i class="fas fa-power-off"></i>
            </a>
        </li>

    <?php else: ?>
        <li><a href="login.php" class="btn-login">CONNEXION</a></li>
    <?php endif; ?>
</ul>
        </nav>
    </header>