<?php 
    require 'config/init.php';

    // Sécurité : Client connecté uniquement
    if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

    $id_cmd = $_GET['id'] ?? 0;
    $msg = "";
    $error = "";

    
    $stmt = $db->getPdo()->prepare("SELECT statut FROM commandes WHERE id = ? AND user_id = ?");
    $stmt->execute([$id_cmd, $_SESSION['user_id']]);
    $cmd = $stmt->fetch();

    if (!$cmd) {
        die("Commande introuvable.");
    }
    

    if ($cmd['statut'] !== 'terminee') {
        $error = "Vous ne pouvez donner votre avis que sur une commande terminée.";
    }

    // TRAITEMENT DU FORMULAIRE
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !$error) {
        $note = intval($_POST['note']);
        $commentaire = htmlspecialchars($_POST['commentaire']);

        // Insertion
        $sql = "INSERT INTO avis (commande_id, note, commentaire, statut, date_avis) VALUES (?, ?, ?, 'en_attente', NOW())";
        $stmtInsert = $db->getPdo()->prepare($sql);
        
        if ($stmtInsert->execute([$id_cmd, $note, $commentaire])) {
            // Redirection vers profil avec succès
            header("Location: profil.php?avis_sent=1");
            exit();
        } else {
            $error = "Erreur lors de l'enregistrement.";
        }
    }

    $titre = "Votre avis compte - Vite & Gourmand";
    include 'header.php'; 
?>

<main class="conteneur small-conteneur">
    <div class="card-box review-card">
        <h2>Votre avis sur la commande #<?php echo $id_cmd; ?></h2>
        
        <?php if($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
            <a href="profil.php" class="btn-secondary">Retour</a>
        <?php else: ?>
            <p>Nous espérons que vous vous êtes régalés !</p>

            <form action="" method="POST">
                <div class="rating-group">
                    <label>Note globale</label>
                    <div class="star-input">
                        <select name="note" class="form-select">
                            <option value="5">★★★★★ - Excellent</option>
                            <option value="4">★★★★☆ - Très bon</option>
                            <option value="3">★★★☆☆ - Correct</option>
                            <option value="2">★★☆☆☆ - Moyen</option>
                            <option value="1">★☆☆☆☆ - Mauvais</option>
                        </select>
                    </div>
                </div>

                <div class="input-group">
                    <label>Votre commentaire</label>
                    <textarea name="commentaire" rows="5" placeholder="Dites-nous ce que vous avez aimé..." required></textarea>
                </div>

                <button type="submit" class="btn-primary width-100">Envoyer mon avis</button>
            </form>
        <?php endif; ?>
    </div>
</main>
<?php include 'footer.php'; ?>