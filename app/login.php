<?php 
    require 'config/init.php'; 

    if (isset($_SESSION['user_id'])) {
        
        if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'employe')) {
            header("Location: admin-dashboard.php");
        } else {
           
            header("Location: profil.php");
        }
        exit();
    }
    $titre = "Connexion";
    $erreur = null;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userManager = new UserManager($db); 
        $user = $userManager->login($_POST['email'], $_POST['password']);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php"); 
            exit();
        } else {
            $erreur = "Identifiants incorrects.";
        }
    }
    include 'header.php';
?>

<main class="auth-page">
    <div class="auth-conteneur" style="max-width: 450px;"> <div class="auth-header">
            <h2>Ravi de vous revoir</h2>
            <p>Connectez-vous pour suivre vos commandes.</p>
        </div>

        <?php if ($erreur): ?>
            <div class="alert error">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $erreur; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="auth-form">
            
            <div class="input-group">
                <label for="email">Adresse Email</label>
                <input type="email" id="email" name="email" placeholder="votre@email.com" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            </div>

            <div class="input-group">
                <div class="label-line">
                    <label for="password">Mot de passe</label>
                    <a href="mot-de-passe-oublie.php" class="forgot-link">Mot de passe oublié ?</a>
                </div>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required placeholder="Votre mot de passe">
                    <i class="fas fa-eye toggle-password" onclick="togglePass()"></i>
                </div>
            </div>

            <button type="submit" class="btn-submit">Se connecter</button>

            <p class="auth-footer">
                Pas encore de compte ? <a href="inscription.php" class="text-orange">Créer un compte</a>
            </p>
        </form>
    </div>
</main>

<script>
    function togglePass() {
        const passInput = document.getElementById('password');
        passInput.type = passInput.type === 'password' ? 'text' : 'password';
    }
</script>

<?php include 'footer.php'; ?>