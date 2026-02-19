<?php 
    $titre = "Contactez-nous - Vite & Gourmand";
    include 'header.php'; 
?>

<main class="conteneur small-conteneur">
    <div class="card-box">
        <h1>Nous contacter</h1>
        <p>Une question sur un menu ou un devis sur mesure ?</p>

        <form action="" method="POST" class="contact-form">
            <div class="input-group">
                <label>Sujet (Titre)</label>
                <input type="text" name="titre" placeholder="Ex: Devis mariage" required>
            </div>

            <div class="input-group">
                <label>Votre Email</label>
                <input type="email" name="email" placeholder="email@exemple.com" required>
            </div>

            <div class="input-group">
                <label>Message (Description)</label>
                <textarea name="description" rows="6" required></textarea>
            </div>

            <button type="submit" class="btn-primary width-100">Envoyer le message</button>
        </form>
    </div>
</main>
<?php include 'footer.php'; ?>