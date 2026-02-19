<?php 
    
    $titre = "Accueil - Vite & Gourmand";
    $nav = "accueil"; 
    include 'header.php'; 
?>

<main>
    <section class="hero conteneur">
        <div class="hero-wrapper">
            <img src="assets/img/traiteur-bg.jpg" alt="Plateau traiteur" class="hero-bg">
            <div class="hero-content glass-effect">
                <h1>Vite & Gourmand, votre traiteur sur mesure</h1>
                <p>Depuis 25 ans à Bordeaux, nous sublimons vos événements. [cite_start]Qualité, fraîcheur et savoir-faire pour Noël, Pâques ou vos repas d'entreprise[cite: 36].</p>
                <a href="menu.php" class="cta-button">Découvrir nos menus</a>
            </div>
        </div>
    </section>

    <section class="team conteneur">
        <h2 class="section-title">Notre Équipe</h2>
        <p style="text-align: center; max-width: 600px; margin: 0 auto 3rem;">
            Derrière chaque plat, il y a la passion de <strong>Julie et José</strong>. 
            Alliant savoir-faire traditionnel et logistique impeccable, nous faisons de votre événement une réussite.
        </p>
        
        <div class="team-grid">
            <div class="team-card">
                <div class="avatar"><i class="fas fa-user-chef"></i></div> <h3>Julie</h3>
                <span class="role text-orange">Cheffe de Cuisine</span>
                <p>25 ans d'expérience culinaire. Elle imagine des cartes créatives qui évoluent au fil des saisons.</p>
            </div>
            
            <div class="team-card">
                <div class="avatar"><i class="fas fa-truck-loading"></i></div>
                <h3>José</h3>
                <span class="role text-orange">Responsable Logistique</span>
                <p>Expert en organisation, il garantit que votre commande arrive à l'heure et dans les meilleures conditions.</p>
            </div>
        </div>
    </section>

    <section class="reviews conteneur">
        <h2 class="section-title">Ce qu'ils pensent de nous</h2>
        <div class="reviews-grid">
            
            <article class="review-card">
                <div class="card-header">
                    <h3>Sophie L.</h3>
                    <span class="verified"><i class="fas fa-check-circle"></i> Avis vérifié</span>
                </div>
                <p>“Vite & Gourmand a sublimé notre repas de Noël. Je recommande sans hésiter.”</p>
                <div class="stars text-orange">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
            </article>

            <article class="review-card">
                <div class="card-header">
                    <h3>Marc D.</h3>
                    <span class="verified"><i class="fas fa-check-circle"></i> Avis vérifié</span>
                </div>
                <p>“Très professionnel et réactif. Les menus sont variés.”</p>
                <div class="stars text-orange">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                </div>
            </article>

            <article class="review-card">
                <div class="card-header">
                    <h3>Claire P.</h3>
                    <span class="verified"><i class="fas fa-check-circle"></i> Avis vérifié</span>
                </div>
                <p>“Jamais déçus, présentation impeccable.”</p>
                <div class="stars text-orange">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
            </article>

        </div>
    </section>
</main>

<?php include 'footer.php'; ?>