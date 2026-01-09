<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit(); 
}

$connexion = getDBConnection();

// Sécurisation des données POST
$nom = $_POST['nom'] ?? '';
$prenom = $_POST['prenom'] ?? '';
$dateNaissance = $_POST['dateNaissance'] ?? '';

// Validation basique
if (empty($nom) || empty($prenom) || empty($dateNaissance)) {
    $resultat = false;
    $error_message = "Tous les champs sont obligatoires.";
} else {
    // Requête préparée sécurisée
    $stmt = mysqli_prepare($connexion, "INSERT INTO adherent (nom, prenom, dateNaissance) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $nom, $prenom, $dateNaissance);
    $resultat = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat de l'ajout</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="container">
        <?php if ($resultat): ?>
            <div class="message">
                L'adhérent a bien été ajouté !
            </div>
        <?php else: ?>
            <div class="message error">
                <?php echo isset($error_message) ? htmlspecialchars($error_message) : "Erreur lors de l'ajout : " . mysqli_error($connexion); ?>
            </div>
        <?php endif; ?>
        
        <div class="logout">
            <a href='ajout-recherche.php'>← Retour au formulaire</a>
        </div>
    </div>
</body>
</html>

<?php mysqli_close($connexion); ?>