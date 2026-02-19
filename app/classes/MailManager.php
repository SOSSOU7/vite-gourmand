<?php
class MailManager {
    private $logFile = __DIR__ . '/../logs/emails.txt';

    public function __construct() {
        // Crée le dossier logs s'il n'existe pas
        if (!is_dir(__DIR__ . '/../logs')) {
            mkdir(__DIR__ . '/../logs');
        }
    }

    public function send($to, $subject, $message) {
        $date = date('Y-m-d H:i:s');
        $content = "[$date] À : $to | Sujet : $subject\nMessage : $message\n--------------------------\n";
        
        // On écrit dans le fichier au lieu d'envoyer réellement
        file_put_contents($this->logFile, $content, FILE_APPEND);
        return true;
    }
}
?>