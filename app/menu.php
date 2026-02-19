<?php 
    require 'config/init.php';
    $titre = "Nos Menus";
    include 'header.php'; 

    $menuManager = new MenuManager($db);
    $menus = $menuManager->getAllMenus(); 
?>

<main class="menu-page">
    
    <div class="conteneur page-header">
        <h1>Nos Menus Gourmands</h1>
        <p>Commandez en ligne pour vos évènements privés ou professionnels.</p>
    </div>

    <section class="filters conteneur">
        <form id="filter-form" onsubmit="return false;">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="search" placeholder="Rechercher un menu...">
            </div>
            
            <div class="filter-options">
                <select id="filter-theme">
                    <option value="">Tous les thèmes</option>
                    <option value="Noël">Noël</option>
                    <option value="Pâques">Pâques</option>
                    <option value="Classique">Classique</option>
                    <option value="Évènement">Évènement</option>
                </select>

                <select id="filter-regime">
                    <option value="">Tous régimes</option>
                    <option value="Végétarien">Végétarien</option>
                    <option value="Végan">Végan</option>
                    <option value="Classique">Classique</option>
                    <option value="Sans Gluten">Sans Gluten</option>
                </select>

                <div class="price-range" style="display:flex; gap:10px; align-items:center;">
                    <input type="number" id="price-min" placeholder="Min €" style="width: 70px; padding: 5px;">
                    <span>à</span>
                    <input type="number" id="price-max" placeholder="Max €" style="width: 70px; padding: 5px;">
                </div>
            </div>
        </form>
    </section>

    <section class="conteneur menu-grid">
        <?php if(count($menus) > 0): ?>
            <?php foreach($menus as $menu): ?>
                <article class="menu-card" 
                         data-theme="<?php echo htmlspecialchars($menu['theme']); ?>" 
                         data-price="<?php echo htmlspecialchars($menu['prix']); ?>"
                         data-regime="<?php echo htmlspecialchars($menu['regime']); ?>"
                         data-title="<?php echo strtolower(htmlspecialchars($menu['titre'])); ?>">
                    
                    <div class="card-img-wrapper">
                        <img src="<?php echo htmlspecialchars($menu['photo']); ?>" alt="<?php echo htmlspecialchars($menu['titre']); ?>">
                        <span class="badge-stock">Stock: <?php echo htmlspecialchars($menu['stock']); ?></span>
                    </div>
                    

                    <div class="card-content">
                        <div class="card-top-info">
                            <span class="tag theme"><?php echo htmlspecialchars($menu['theme']); ?></span>
                            <span class="tag regime"><?php echo htmlspecialchars($menu['regime']); ?></span>
                        </div>

                        <h3><?php echo htmlspecialchars($menu['titre']); ?></h3>
                        <p class="description"><?php echo htmlspecialchars($menu['description']); ?></p>

                        <div class="card-details">
                            <div class="detail-item">
                                <i class="fas fa-users"></i>
                                <span>Min. <?php echo htmlspecialchars($menu['min_pers']); ?> pers.</span>
                            </div>
                            <div class="price">
                                <?php echo htmlspecialchars($menu['prix']); ?>€ <small>/pers</small>
                            </div>
                        </div>

<a href="detail-menu.php?id=<?php echo $menu['id']; ?>" class="btn-card">Commander</a>

<?php if(isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'employe')): ?>
    <div class="admin-actions" style="margin-top: 10px; display: flex; gap: 5px;">
        <a href="admin-menu-form.php?id=<?php echo $menu['id']; ?>" class="btn-small" style="background:#f39c12; color:white; flex:1; text-align:center;">Modifier</a>
        
        <a href="admin-menu-delete.php?id=<?php echo $menu['id']; ?>" class="btn-small" style="background:#c0392b; color:white; flex:1; text-align:center;" onclick="return confirm('Supprimer ce menu ?');">Supprimer</a>
    </div>
<?php endif; ?>                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center; width:100%;">Aucun menu disponible pour le moment.</p>
        <?php endif; ?>
    </section>

</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const filterTheme = document.getElementById('filter-theme');
        const filterRegime = document.getElementById('filter-regime');
        const priceMinInput = document.getElementById('price-min');
        const priceMaxInput = document.getElementById('price-max');
        const searchInput = document.getElementById('search');
        const cards = document.querySelectorAll('.menu-card');

        function filterMenus() {
            const selectedTheme = filterTheme.value;
            const selectedRegime = filterRegime.value;
            const minPrice = parseFloat(priceMinInput.value) || 0;
            const maxPrice = parseFloat(priceMaxInput.value) || 10000;
            const searchText = searchInput.value.toLowerCase().trim();

            cards.forEach(card => {
                const cardTheme = card.dataset.theme;
                const cardRegime = card.dataset.regime;
                const cardPrice = parseFloat(card.dataset.price);
                const cardTitle = card.dataset.title;

                let matchTheme = (selectedTheme === "") || (cardTheme === selectedTheme);
                let matchRegime = (selectedRegime === "") || (cardRegime === selectedRegime);
                let matchPrice = (cardPrice >= minPrice) && (cardPrice <= maxPrice);
                let matchSearch = (searchText === "") || cardTitle.includes(searchText);

                if (matchTheme && matchRegime && matchPrice && matchSearch) {
                    card.style.display = "flex";
                } else {
                    card.style.display = "none";
                }
            });
        }

        filterTheme.addEventListener('change', filterMenus);
        filterRegime.addEventListener('change', filterMenus);
        searchInput.addEventListener('input', filterMenus);
        priceMinInput.addEventListener('input', filterMenus);
        priceMaxInput.addEventListener('input', filterMenus);
    });
</script>

<?php include 'footer.php'; ?>