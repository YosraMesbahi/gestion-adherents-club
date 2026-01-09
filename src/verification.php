<?php
session_start();
require_once '../config.php';

if (isset($_POST['login']) && isset($_POST['password'])) {
    $connexion = getDBConnection();
    
    $login = mysqli_real_escape_string($connexion, $_POST['login']);
    $password_saisi = $_POST['password'];

    // Utilisation de requête préparée (sécurisée)
    $stmt = mysqli_prepare($connexion, "SELECT * FROM user WHERE login = ?");
    mysqli_stmt_bind_param($stmt, "s", $login);
    mysqli_stmt_execute($stmt);
    $resultat = mysqli_stmt_get_result($stmt);
    $utilisateur = mysqli_fetch_assoc($resultat);

    if ($utilisateur) {
        $hashed_password = $utilisateur['password'];
        
        if (hash_equals($hashed_password, crypt($password_saisi, $hashed_password))) {
            $_SESSION['login'] = $utilisateur['login'];
            $_SESSION['last_activity'] = time();
            header("Location: ajout-recherche.php");
            exit();
        } else {
            $error_message = "Mot de passe incorrect.";
        }
    } else {
        $error_message = "Identifiants incorrects.";
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($connexion);
} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur de connexion</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="container">
        <div class="message error">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
        <div class="logout">
            <a href="login.php">← Réessayer</a>
        </div>
    </div>
</body>
</html>