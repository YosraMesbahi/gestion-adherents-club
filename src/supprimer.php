<?php
session_start();

// 1. Vérification de la session
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

// 2. Vérification de la présence de l'ID
if (isset($_GET['id'])) {
    // Connexion
    $connexion = mysqli_connect("mysql-mesbahi.alwaysdata.net", "mesbahi", "M@s@bih4620!+", "mesbahi_club");
    
    // Sécurisation de l'ID
    $id = mysqli_real_escape_string($connexion, $_GET['id']);
    
    // 3. Requête de suppression
    $sql = "DELETE FROM adherent WHERE id = $id";
    
    $resultat = mysqli_query($connexion, $sql);
    mysqli_close($connexion);
} else {
    // Si pas d'ID, on retourne à l'accueil
    header("Location: ajout-recherche.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppression d'un adhérent</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Suppression d'un adhérent</h1>
        
        <?php if ($resultat): ?>
            <div class="message">
                ✓ Adhérent supprimé avec succès !
            </div>
        <?php else: ?>
            <div class="message error">
                ✗ Erreur lors de la suppression de l'adhérent.
            </div>
        <?php endif; ?>
        
        <div class="logout">
            <a href='ajout-recherche.php'>← Retour à la recherche</a>
        </div>
    </div>
</body>
</html>