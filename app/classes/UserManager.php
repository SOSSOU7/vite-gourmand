<?php
class UserManager {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db->getPdo();
    }

    // Inscription
    public function register($nom, $prenom, $email, $tel, $adresse, $password) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) return "Cet email est déjà utilisé.";

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/', $password)) {
            return "Le mot de passe doit contenir 10 caractères, 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial.";
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (nom, prenom, email, password, tel, adresse, role) VALUES (?, ?, ?, ?, ?, ?, 'client')";
        
        if ($this->db->prepare($sql)->execute([$nom, $prenom, $email, $hash, $tel, $adresse])) {
            return true;
        }
        return "Erreur lors de l'inscription.";
    }

    // Connexion
    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // On retourne l'utilisateur pour la session
            return $user;
        }
        return false;
    }

    // Récupérer un utilisateur par ID
    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Mise à jour profil
    public function updateProfile($id, $nom, $prenom, $tel, $adresse) {
        $sql = "UPDATE users SET nom=?, prenom=?, tel=?, adresse=? WHERE id=?";
        return $this->db->prepare($sql)->execute([$nom, $prenom, $tel, $adresse, $id]);
    }
    
    // Création d'un employé par l'admin
    public function createEmployee($email, $password) {
        // Vérif si email existe
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) return "Email déjà pris.";

        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (nom, prenom, email, password, role) VALUES ('Employé', 'Nouveau', ?, ?, 'employe')";
        
        return $this->db->prepare($sql)->execute([$email, $hash]);
    }

    // Récupérer tous les utilisateurs (pour l'admin)
    public function getAllUsers() {
        return $this->db->query("SELECT * FROM users ORDER BY role, nom")->fetchAll();
    }

    // Bannir ou Supprimer
    public function deleteUser($id) {
        return $this->db->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
    }
    
    // Changer le statut (ex: Bloquer un compte - optionnel)
    public function toggleBan($id) {
       
        return $this->deleteUser($id);
    }
}