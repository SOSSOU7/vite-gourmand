<?php 
    require 'config/init.php';
    $titre = "Réinitialisation Mot de Passe";
    $msg = null;
    $error = null;

    // Vérification de la présence du token (même s'il n'est pas vérifié en BDD ici, il doit être dans l'URL)
    if (!isset($_GET['token']) && $_SERVER["REQUEST_METHOD"] != "POST") {
        header("Location: login.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $confirm = $_POST['confirm_password'];

        if ($pass !== $confirm) {
            $error = "Les mots de passe ne correspondent pas.";
        } 
        // Règle de sécurité (10 chars, Maj, Min, Chiffre, Spécial)
        elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/', $pass)) {
            $error = "Le mot de passe doit contenir 10 caractères, 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial.";
        } else {
            // Mise à jour du mot de passe en base
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $db->getPdo()->prepare("UPDATE users SET password = ? WHERE email = ?");
            
            if ($stmt->execute([$hash, $email])) {
                // On vérifie si une ligne a bien été modifiée (si l'email existe)
                if ($stmt->rowCount() > 0) {
                    $msg = "Votre mot de passe a été modifié avec succès.";
                } else {
                    $error = "Aucun compte trouvé avec cet email.";
                }
            } else {
                $error = "Erreur technique lors de la mise à jour.";
            }
        }
    }
    include 'header.php'; 
?>

<main class="auth-page">
    <div class="auth-conteneur" style="max-width: 450px;">
        <div class="auth-header">
            <h2>Nouveau mot de passe</h2>
            <p>Sécurisez votre compte.</p>
        </div>

        <?php if ($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($msg): ?>
            <div class="alert success"><?php echo $msg; ?></div>
            <p class="auth-footer">
                <a href="login.php" class="btn-submit" style="display:block; text-align:center; text-decoration:none;">Se connecter</a>
            </p>
        <?php else: ?>

        <form action="" method="POST">
            <div class="input-group">
                <label for="email">Confirmez votre Email</label>
                <input type="email" id="email" name="email" required placeholder="votre@email.com">
            </div>

            <div class="input-group">
                <label for="password">Nouveau mot de passe</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required placeholder="Nouveau mot de passe">
                </div>
            </div>

            <div class="input-group">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <div class="password-wrapper">
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Répétez le mot de passe">
                </div>
            </div>
            
            <button type="submit" class="btn-submit">Valider</button>
        </form>
        <?php endif; ?>
    </div>
</main>

<?php include 'footer.php'; ?>