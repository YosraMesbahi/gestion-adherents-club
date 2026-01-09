<?php
/**
 * Fichier de configuration exemple
 * 
 * Instructions :
 * 1. Copiez ce fichier et renommez-le en "config.php"
 * 2. Remplissez les informations de votre base de données
 * 3. NE COMMITTEZ JAMAIS le fichier config.php !
 */

// Configuration de la base de données
define('DB_HOST', 'votre_serveur_mysql');
define('DB_USER', 'votre_utilisateur');
define('DB_PASS', 'votre_mot_de_passe');
define('DB_NAME', 'votre_base_de_donnees');

// Configuration de la session
define('SESSION_LIFETIME', 3600);

/**
 * Fonction pour obtenir une connexion à la base de données
 * @return mysqli
 */
function getDBConnection() {
    $connexion = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if (!$connexion) {
        die("Erreur de connexion à la base de données : " . mysqli_connect_error());
    }
    
    mysqli_set_charset($connexion, "utf8mb4");
    return $connexion;
}