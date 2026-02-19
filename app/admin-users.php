<?php 
    require 'config/init.php';
    // Sécurité : ADMIN SEULEMENT
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { header("Location: index.php"); exit(); }

    $userManager = new UserManager($db);
    $mailManager = new MailManager();
    $msg = "";

    // Création
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_employee'])) {
        if ($userManager->createEmployee($_POST['email'], $_POST['password'])) {
            $msg = "Employé créé !";
            $mailManager->send($_POST['email'], "Bienvenue", "Compte employé créé.");
        } else { $msg = "Erreur création."; }
    }
    // Suppression
    if (isset($_GET['delete'])) {
        $userManager->deleteUser($_GET['delete']);
        header("Location: admin-users.php"); exit();
    }

    $users = $userManager->getAllUsers();
    $titre = "Utilisateurs";
    include 'header.php';
?>

<div class="admin-layout">
    <aside class="sidebar">
        <?php include 'includes/sidebar-admin.php'; ?>
    </aside>

    <main class="admin-content">
        <h1>Gestion Utilisateurs</h1>
        <?php if($msg) echo "<div class='alert success'>$msg</div>"; ?>

        <div class="card-box mb-30">
            <h3>Nouveau Compte Employé</h3>
            <form method="POST" class="inline-form">
                <input type="email" name="email" placeholder="Email pro" required>
                <input type="text" name="password" placeholder="Mot de passe" required>
                <button type="submit" name="create_employee" class="btn-primary">Créer</button>
            </form>
        </div>

        <div class="table-conteneur">
            <table class="data-table">
                <thead><tr><th>Nom</th><th>Email</th><th>Rôle</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach($users as $u): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u['nom'] . ' ' . $u['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><span class="badge"><?php echo $u['role']; ?></span></td>
                        <td>
                            <?php if($u['role'] != 'admin'): ?>
                                <a href="?delete=<?php echo $u['id']; ?>" class="btn-icon delete" onclick="return confirm('Supprimer ?')"><i class="fas fa-trash"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>