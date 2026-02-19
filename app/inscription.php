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
    
    $titre = "Créer un compte - Vite & Gourmand";
    $erreur = null;
    $succes = null;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userManager = new UserManager($db);

        
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $tel = htmlspecialchars($_POST['tel']);
        $adresse = htmlspecialchars($_POST['adresse']);
        $password = $_POST['password'];

        
        $result = $userManager->register($nom, $prenom, $email, $tel, $adresse, $password);

        if ($result === true) {
            $succes = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
        } else {
           
            $erreur = $result; 
        }
    }
    include 'header.php'; 
?>

<main class="auth-page">
    <div class="auth-conteneur">
        <div class="auth-header">
            <h2>Bienvenue chez nous</h2>
            <p>Créez votre compte pour commander vos menus préférés.</p>
        </div>

        <?php if ($erreur): ?>
            <div class="alert error"><?php echo $erreur; ?></div>
        <?php endif; ?>
        <?php if ($succes): ?>
            <div class="alert success"><?php echo $succes; ?></div>
        <?php endif; ?>

        <form action="" method="POST" class="auth-form">
            <div class="form-row">
                <div class="input-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
                </div>
                <div class="input-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Votre prénom" required>
                </div>
            </div>

            <div class="form-row">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="exemple@mail.com" required>
                </div>
                <div class="input-group">
                    <label for="tel">Téléphone (GSM)</label>
                    <input type="tel" id="tel" name="tel" placeholder="06 12 34 56 78" required>
                </div>
            </div>

            <div class="input-group">
                <label for="adresse">Adresse postale</label>
                <textarea id="adresse" name="adresse" rows="2" placeholder="N° rue, Code postal, Ville" required></textarea>
                <small class="hint">Nécessaire pour la livraison.</small>
            </div>

            <div class="input-group">
                <label for="password">Mot de passe</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required placeholder="Votre mot de passe sécurisé">
                    <i class="fas fa-eye toggle-password" onclick="togglePass()"></i>
                </div>
                <ul class="password-rules">
                    <li id="rule-length" class="invalid">10 caractères min.</li>
                    <li id="rule-upper" class="invalid">1 Majuscule</li>
                    <li id="rule-lower" class="invalid">1 Minuscule</li>
                    <li id="rule-number" class="invalid">1 Chiffre</li>
                    <li id="rule-special" class="invalid">1 Caractère spécial</li>
                </ul>
            </div>

            <div class="input-group checkbox-group">
                <input type="checkbox" id="rgpd" name="rgpd" required>
                <label for="rgpd">J'accepte que mes données soient traitées pour la gestion de ma commande (RGPD).</label>
            </div>

            <button type="submit" class="btn-submit">Créer mon compte</button>

            <p class="auth-footer">
                Déjà un compte ? <a href="login.php" class="text-orange">Se connecter</a>
            </p>
        </form>
    </div>
</main>
<script>
    function togglePass() {
        const passInput = document.getElementById('password');
        passInput.type = passInput.type === 'password' ? 'text' : 'password';
    }
    document.getElementById('password').addEventListener('input', function() {
        const val = this.value;
        const rules = {
            'length': val.length >= 10,
            'upper': /[A-Z]/.test(val),
            'lower': /[a-z]/.test(val),
            'number': /[0-9]/.test(val),
            'special': /[!@#$%^&*(),.?":{}|<>]/.test(val)
        };
        for (const [key, valid] of Object.entries(rules)) {
            const el = document.getElementById('rule-' + key);
            el.className = valid ? 'valid' : 'invalid';
        }
    });
</script>
<?php include 'footer.php'; ?>