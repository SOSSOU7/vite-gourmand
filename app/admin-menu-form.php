<?php 
    require 'config/init.php';
    if (!isset($_SESSION['role']) || $_SESSION['role'] == 'client') { header("Location: index.php"); exit(); }

    $menuManager = new MenuManager($db);
    $msg = "";
    
   
    $menuToEdit = null;
    $isEdit = false;
    if (isset($_GET['id'])) {
        $menuToEdit = $menuManager->getMenuById($_GET['id']);
        if ($menuToEdit) $isEdit = true;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($isEdit) {
            // Mise à jour
            $file = (isset($_FILES['image']) && $_FILES['image']['size'] > 0) ? $_FILES['image'] : null;
            if ($menuManager->updateMenu($_GET['id'], $_POST, $file)) {
                header("Location: menu.php?msg=updated"); exit();
            } else {
                $msg = "Erreur mise à jour.";
            }
        } else {
            // Création
            if ($menuManager->addMenu($_POST, $_FILES['image'])) {
                header("Location: menu.php?msg=created"); exit();
            } else {
                $msg = "Erreur création.";
            }
        }
    }
    
    $titre = ($isEdit ? "Modifier" : "Ajouter") . " un Menu";
    include 'header.php';
?>

<div class="admin-layout">
    <aside class="sidebar">
        <?php include 'includes/sidebar-admin.php'; ?>
    </aside>

    <main class="admin-content">
        <div class="content-header">
            <h1><?php echo $isEdit ? "Modifier le Menu" : "Nouveau Menu"; ?></h1>
            <a href="menu.php" class="btn-secondary">Annuler</a>
        </div>

        <?php if($msg): ?><div class="alert error"><?php echo $msg; ?></div><?php endif; ?>

        <div class="card-box">
            <form action="" method="POST" enctype="multipart/form-data" class="admin-form">
                
                <div class="form-row">
                    <div class="input-group">
                        <label>Titre</label>
                        <input type="text" name="titre" value="<?php echo $isEdit ? htmlspecialchars($menuToEdit['titre']) : ''; ?>" required>
                    </div>
                    <div class="input-group">
                        <label>Thème</label>
                        <select name="theme">
                            <?php $curr = $isEdit ? $menuToEdit['theme'] : ''; ?>
                            <option value="Classique" <?php if($curr=='Classique') echo 'selected'; ?>>Classique</option>
                            <option value="Noël" <?php if($curr=='Noël') echo 'selected'; ?>>Noël</option>
                            <option value="Pâques" <?php if($curr=='Pâques') echo 'selected'; ?>>Pâques</option>
                            <option value="Évènement" <?php if($curr=='Évènement') echo 'selected'; ?>>Évènement</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="input-group"><label>Prix (€)</label><input type="number" name="prix" step="0.01" value="<?php echo $isEdit ? $menuToEdit['prix'] : ''; ?>" required></div>
                    <div class="input-group"><label>Min Pers.</label><input type="number" name="min_pers" value="<?php echo $isEdit ? $menuToEdit['min_pers'] : '4'; ?>" required></div>
                </div>

                <div class="input-group"><label>Description</label><textarea name="description" rows="3"><?php echo $isEdit ? htmlspecialchars($menuToEdit['description']) : ''; ?></textarea></div>

                <div class="form-row">
                    <div class="input-group">
                        <label>Photo <?php echo $isEdit ? '(Laisser vide pour garder)' : ''; ?></label>
                        <input type="file" name="image" accept="image/*" <?php echo $isEdit ? '' : 'required'; ?>>
                    </div>
                    <div class="input-group"><label>Régime</label><input type="text" name="regime" value="<?php echo $isEdit ? htmlspecialchars($menuToEdit['regime']) : ''; ?>"></div>
                </div>
                
                <?php if(!$isEdit): ?>
                <hr>
                <div class="plats-section">
                    <h3>Composition (Création uniquement)</h3>
                    <div class="form-row"><div class="input-group"><label>Entrée</label><input type="text" name="entree"></div><div class="input-group"><label>Allergènes</label><input type="text" name="allergenes_entree"></div></div>
                    <div class="form-row"><div class="input-group"><label>Plat</label><input type="text" name="plat"></div><div class="input-group"><label>Allergènes</label><input type="text" name="allergenes_plat"></div></div>
                    <div class="form-row"><div class="input-group"><label>Dessert</label><input type="text" name="dessert"></div><div class="input-group"><label>Allergènes</label><input type="text" name="allergenes_dessert"></div></div>
                </div>
                <?php endif; ?>

                <button type="submit" class="btn-primary width-100"><?php echo $isEdit ? "Mettre à jour" : "Enregistrer"; ?></button>
            </form>
        </div>
    </main>
</div>