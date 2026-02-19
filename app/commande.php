<?php 
    require 'config/init.php';
    
    if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

    $menuManager = new MenuManager($db);
    $userManager = new UserManager($db);
    $orderManager = new OrderManager($db);

    $menu_id = $_GET['menu'] ?? 0;
    $menu_choisi = $menuManager->getMenuById($menu_id);
    $user = $userManager->getUserById($_SESSION['user_id']);

    if (!$menu_choisi) die("Menu introuvable");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Calcul du prix
        $prix_total = $orderManager->calculateTotal(
            $menu_choisi['prix'], 
            $menu_choisi['min_pers'], 
            intval($_POST['nb_pers']), 
            intval($_POST['distance'])
        );

        // Création commande SQL
        if ($orderManager->createOrder($_SESSION['user_id'], $menu_id, $_POST, $prix_total)) {
            
            // --- AJOUT NoSQL (JSON) ---
            require_once 'classes/StatsManager.php';
            $statsManager = new StatsManager();
            $statsManager->recordOrder($menu_choisi['titre'], $prix_total);
            // --------------------------

            header("Location: profil.php?succes=1");
            exit();
        }
    }
    include 'header.php';
?>
<main class="order-page">
    <div class="conteneur">
        <h1 class="page-title">Finaliser votre commande</h1>
        <form action="" method="POST" class="order-grid">
            
            <div class="form-section">
                <section class="step-box">
                    <h3><i class="fas fa-user"></i> Vos coordonnées</h3>
                    <div class="form-row">
                        <div class="input-group">
                            <label>Nom</label>
                            <input type="text" value="<?php echo htmlspecialchars($user['nom']); ?>" readonly class="readonly-input">
                        </div>
                        <div class="input-group">
                            <label>Prénom</label>
                            <input type="text" value="<?php echo htmlspecialchars($user['prenom']); ?>" readonly class="readonly-input">
                        </div>
                    </div>
                </section>

                <section class="step-box">
                    <h3><i class="fas fa-truck"></i> Livraison</h3>
                    <div class="input-group">
                        <label for="adresse">Adresse de livraison</label>
                        <textarea id="adresse" name="adresse" rows="2" required><?php echo htmlspecialchars($user['adresse']); ?></textarea>
                    </div>
                    <div class="input-group">
                        <label for="distance">Distance depuis Bordeaux (km)</label>
                        <input type="number" id="distance" name="distance" min="0" value="0" required oninput="updateTotal()">
                        <small class="hint">Laissez à 0 si dans Bordeaux intramuros (Forfait 5€). Sinon +0.59€/km.</small>
                    </div>
                    <div class="form-row">
                        <div class="input-group">
                            <label for="date">Date souhaitée</label>
                            <input type="date" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="input-group">
                            <label for="heure">Heure</label>
                            <input type="time" id="heure" name="heure" min="09:00" max="20:00" required>
                        </div>
                    </div>
                </section>

                <section class="step-box">
                    <h3><i class="fas fa-users"></i> Invités</h3>
                    <div class="input-group">
                        <label>Nombre de personnes (Min: <?php echo $menu_choisi['min_pers']; ?>)</label>
                        <input type="number" id="nb_pers" name="nb_pers" 
                               min="<?php echo $menu_choisi['min_pers']; ?>" 
                               value="<?php echo $menu_choisi['min_pers']; ?>" 
                               required oninput="updateTotal()">
                    </div>
                    <div class="info-bulle" id="promo-msg" style="display:none;">
                        <i class="fas fa-gift"></i> Super ! Une réduction de 10% est appliquée (+5 pers).
                    </div>
                </section>
            </div>

            <div class="summary-section">
                <div class="summary-card">
                    <h2>Récapitulatif</h2>
                    <div class="menu-preview">
                        <strong><?php echo htmlspecialchars($menu_choisi['titre']); ?></strong>
                        <span class="price-unit"><?php echo $menu_choisi['prix']; ?>€ / pers</span>
                    </div>
                    <hr>
                    <div class="calc-row">
                        <span>Sous-total (<span id="lbl-pers"><?php echo $menu_choisi['min_pers']; ?></span> pers)</span>
                        <span id="subtotal">0.00€</span>
                    </div>
                    <div class="calc-row">
                        <span>Frais de livraison</span>
                        <span id="shipping-cost">5.00€</span>
                    </div>
                    <div class="calc-row promo" id="row-promo" style="display:none;">
                        <span>Réduction (-10%)</span>
                        <span id="discount">-0.00€</span>
                    </div>
                    <hr>
                    <div class="total-row">
                        <span>TOTAL À PAYER</span>
                        <span id="final-total">0.00€</span>
                    </div>
                    <button type="submit" class="btn-order-confirm">Valider et Payer</button>
                    <p class="secure-text"><i class="fas fa-lock"></i> Paiement sur place ou à la livraison</p>
                </div>
            </div>
        </form>
    </div>
</main>
<script>
    const basePrice = <?php echo floatval($menu_choisi['prix']); ?>;
    const minPers = <?php echo intval($menu_choisi['min_pers']); ?>;
    
    function updateTotal() {
        let qtyInput = document.getElementById('nb_pers');
        let kmInput = document.getElementById('distance');
        let qty = parseInt(qtyInput.value) || minPers;
        let km = parseInt(kmInput.value) || 0;
        
        if(qty < minPers) { qty = minPers; }

        let subtotal = qty * basePrice;
        let shipping = 5.00; 
        if(km > 0) { shipping += (km * 0.59); }

        let discount = 0;
        let isPromo = qty >= (minPers + 5);
        
        const promoMsg = document.getElementById('promo-msg');
        const promoRow = document.getElementById('row-promo');

        if (isPromo) {
            discount = subtotal * 0.10;
            if(promoMsg) promoMsg.style.display = 'block';
            if(promoRow) promoRow.style.display = 'flex';
        } else {
            if(promoMsg) promoMsg.style.display = 'none';
            if(promoRow) promoRow.style.display = 'none';
        }

        let total = subtotal + shipping - discount;

        document.getElementById('lbl-pers').innerText = qty;
        document.getElementById('subtotal').innerText = subtotal.toFixed(2) + '€';
        document.getElementById('shipping-cost').innerText = shipping.toFixed(2) + '€';
        document.getElementById('discount').innerText = '-' + discount.toFixed(2) + '€';
        document.getElementById('final-total').innerText = total.toFixed(2) + '€';
    }
    document.addEventListener('DOMContentLoaded', updateTotal);
</script>
<?php include 'footer.php'; ?>