<?php 
    require 'config/init.php';

    $menuManager = new MenuManager($db);
    
    // On instancie le ReviewManager
    $reviewManager = new ReviewManager($db);

    $menu_id = $_GET['id'] ?? 1; 
    $menu = $menuManager->getMenuById($menu_id);

    if (!$menu) {
        header("Location: menu.php");
        exit();
    }

    // Récupération des avis du menu
    $avis_clients = $reviewManager->getReviewsByMenu($menu_id);
    
    // Calcul note moyenne
    $note_moyenne = 0;
    if(count($avis_clients) > 0) {
        $total = 0;
        foreach($avis_clients as $av) $total += $av['note'];
        $note_moyenne = round($total / count($avis_clients), 1);
    }

    // Récupération et formatage composition
    $rawComposition = $menuManager->getMenuComposition($menu_id);
    $data = $menuManager->formatComposition($rawComposition);
    
    $composition = $data['plats'];      
    $allergenes_str = $data['allergenes'];

    $titre = $menu['titre'] . " - Vite & Gourmand";
    $nav = "menu";
    include 'header.php'; 
?>

<main class="detail-page conteneur">
    <div class="breadcrumb">
        <a href="menu.php"><i class="fas fa-arrow-left"></i> Retour aux menus</a>
    </div>

    <div class="detail-grid">
        <section class="gallery-section">
            <div class="main-image">
                <img src="<?php echo htmlspecialchars($menu['photo']); ?>" alt="<?php echo htmlspecialchars($menu['titre']); ?>">
            </div>
        </section>

        <section class="info-section">
            <div class="header-info">
                <div class="tags">
                    <span class="tag theme"><?php echo htmlspecialchars($menu['theme']); ?></span>
                    <span class="tag regime"><?php echo htmlspecialchars($menu['regime']); ?></span>
                </div>
                <h1><?php echo htmlspecialchars($menu['titre']); ?></h1>
                <p class="description"><?php echo nl2br(htmlspecialchars($menu['description'])); ?></p>
            </div>

            <div class="price-block">
                <span class="price"><?php echo htmlspecialchars($menu['prix']); ?>€ <small>/ pers.</small></span>
                <span class="min-pers text-muted">Minimum <?php echo htmlspecialchars($menu['min_pers']); ?> personnes</span>
            </div>

            <div class="menu-composition">
                <h3>Au menu</h3>
                <div class="course-list">
                    <div class="course-item">
                        <div class="icon"><i class="fas fa-carrot"></i></div>
                        <div class="text"><strong>Entrée</strong><p><?php echo htmlspecialchars($composition['Entrée']); ?></p></div>
                    </div>
                    <div class="course-item">
                        <div class="icon"><i class="fas fa-utensils"></i></div>
                        <div class="text"><strong>Plat</strong><p><?php echo htmlspecialchars($composition['Plat']); ?></p></div>
                    </div>
                    <div class="course-item">
                        <div class="icon"><i class="fas fa-ice-cream"></i></div>
                        <div class="text"><strong>Dessert</strong><p><?php echo htmlspecialchars($composition['Dessert']); ?></p></div>
                    </div>
                </div>
                <div class="allergenes-box">
                    <i class="fas fa-exclamation-circle"></i>
                    <p><strong>Allergènes présents :</strong> <?php echo htmlspecialchars($allergenes_str); ?></p>
                </div>
            </div>
            
            <div class="conditions-grid">
                <div class="cond-item"><i class="fas fa-clock"></i><span><?php echo htmlspecialchars($menu['delai_commande']); ?></span></div>
                <div class="cond-item stock"><i class="fas fa-box-open"></i><span>Stock : <?php echo htmlspecialchars($menu['stock']); ?></span></div>
            </div>

            <div class="action-area">
                <a href="commande.php?menu=<?php echo $menu['id']; ?>" class="btn-order">Commander ce menu</a>
            </div>
        </section>
    </div>

    <section class="reviews-section" style="margin-top: 4rem; border-top: 1px solid #eee; padding-top: 2rem;">
        <h2 class="section-title" style="text-align:left; font-size:1.5rem; margin-bottom:1.5rem;">
            Avis Clients 
            <?php if($note_moyenne > 0): ?>
                <span style="font-size: 0.8em; color: var(--primary);">
                    (<?php echo $note_moyenne; ?>/5 <i class="fas fa-star"></i>)
                </span>
            <?php endif; ?>
        </h2>

        <?php if(count($avis_clients) > 0): ?>
            <div class="reviews-grid">
                <?php foreach($avis_clients as $avis): ?>
                    <article class="review-card">
                        <div class="card-header">
                            <h3><?php echo htmlspecialchars($avis['prenom']); ?></h3>
                            <span class="verified" style="font-size:0.8rem; color:#888;">
                                Le <?php echo date("d/m/Y", strtotime($avis['date_avis'])); ?>
                            </span>
                        </div>
                        <div class="stars text-orange" style="margin-bottom: 10px;">
                            <?php for($i=0; $i<$avis['note']; $i++) echo '<i class="fas fa-star"></i>'; ?>
                            <?php for($i=$avis['note']; $i<5; $i++) echo '<i class="far fa-star"></i>'; ?>
                        </div>
                        <p>“<?php echo htmlspecialchars($avis['commentaire']); ?>”</p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="color:#666;">Aucun avis pour ce menu pour le moment.</p>
        <?php endif; ?>
    </section>

</main>
<?php include 'footer.php'; ?>