<?php
require 'config/init.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] == 'client') { header("Location: index.php"); exit(); }

if (isset($_GET['id'])) {
    $menuManager = new MenuManager($db);
    $menuManager->deleteMenu($_GET['id']);
}
header("Location: menu.php?msg=deleted");
exit();
?>