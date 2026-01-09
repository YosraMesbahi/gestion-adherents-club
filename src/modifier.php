<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

$connexion = mysqli_connect("mysql-mesbahi.alwaysdata.net", "mesbahi", "M@s@bih4620!+", "mesbahi_club");

// --- PARTIE 1 : Traitement de la mise à jour (si le formulaire est envoyé) ---
if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($connexion, $_POST['id']);
    $nom = mysqli_real_escape_string($connexion, $_POST['nom']);
    $prenom = mysqli_real_escape_string($connexion, $_POST['prenom']);
    $dateN = mysqli_real_escape_string($connexion, $_POST['dateNaissance']);

    $sql_update = "UPDATE adherent SET nom='$nom', prenom='$prenom', dateNaissance='$dateN' WHERE id=$id";
    
    if (mysqli_query($connexion, $sql_update)) {
        $message = "Modifications enregistrées avec succès !";
        $message_class = "message";
    } else {
        $message = "Erreur lors de la mise à jour : " . mysqli_error($connexion);
        $message_class = "message error";
    }
}

// --- PARTIE 2 : Affichage du formulaire avec les données actuelles ---
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connexion, $_GET['id']);
    $resultat = mysqli_query($connexion, "SELECT * FROM adherent WHERE id=$id");
    $donnees = mysqli_fetch_assoc($resultat);
} else {
    header("Location: ajout-recherche.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un adhérent</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Modifier les informations</h1>
        
        <?php if (isset($message)): ?>
            <div class="<?php echo $message_class; ?>">
                <?php echo $message; ?>
                <br><br>
                <a href='ajout-recherche.php'>Retour à la recherche</a>
            </div>
        <?php endif; ?>
        
        <form action="modifier.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $donnees['id']; ?>">

            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($donnees['nom']); ?>" required>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($donnees['prenom']); ?>" required>

            <label for="dateNaissance">Date de Naissance :</label>
            <input type="date" id="dateNaissance" name="dateNaissance" value="<?php echo htmlspecialchars($donnees['dateNaissance']); ?>" required>

            <button type="submit" name="update">Valider les modifications</button>
        </form>

        <div class="logout">
            <a href="ajout-recherche.php">← Retourner à l'accueil</a>
        </div>
    </div>
</body>
</html>

<?php mysqli_close($connexion); ?>