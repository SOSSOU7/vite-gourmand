<?php 
    $titre = "Réinitialisation - Vite & Gourmand";
    include 'header.php'; 
    require 'config/init.php';
    
    $msg = null;
    $mailManager = new MailManager();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $token = bin2hex(random_bytes(16));
        $link = "http://localhost/vite-gourmand/reset.php?token=" . $token;
        
        $mailManager->send($email, "Réinitialisation mot de passe", "Cliquez ici pour changer votre mot de passe : " . $link);
        
        $msg = "Si ce compte existe, un email contenant un lien de réinitialisation vient d'être envoyé.";
    }
?>

<main class="auth-page">
    <div class="auth-conteneur" style="max-width: 450px;">
        <div class="auth-header">
            <h2>Mot de passe oublié ?</h2>
            <p>Entrez votre email pour recevoir un lien.</p>
        </div>

        <?php if ($msg): ?>
            <div class="alert success"><?php echo $msg; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="input-group">
                <label for="email">Votre Email</label>
                <input type="email" id="email" name="email" required placeholder="exemple@mail.com">
            </div>
            <button type="submit" class="btn-submit">Envoyer le lien</button>
            <p class="auth-footer">
                <a href="login.php" style="color: #666;"><i class="fas fa-arrow-left"></i> Retour à la connexion</a>
            </p>
        </form>
    </div>
</main>
<?php include 'footer.php'; ?>