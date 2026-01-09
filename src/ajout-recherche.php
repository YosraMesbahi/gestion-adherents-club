<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Adhérents</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="container">
        <h1>Gestion des Adhérents</h1>
        
        <div class="section">
            <h2>Ajouter un membre</h2>
            <form action="traitement-ajout.php" method="POST">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" required>

                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" required>

                <label for="dateNaissance">Date de naissance :</label>
                <input type="date" id="dateNaissance" name="dateNaissance" required>

                <button type="submit">Ajouter</button>
            </form>
        </div>

        <div class="section">
            <h2>Rechercher un membre</h2>
            <form action="ajout-recherche.php" method="GET" class="search-form">
                <input type="text" name="query" placeholder="Nom ou prénom de l'adhérent..." required>
                <button type="submit">Rechercher</button>
            </form>
        </div>

        <?php
        // RECHERCHE
        if (isset($_GET['query'])) {
            $connexion = getDBConnection();
            $recherche = mysqli_real_escape_string($connexion, $_GET['query']);
            
            // Requête préparée sécurisée
            $stmt = mysqli_prepare($connexion, "SELECT * FROM adherent WHERE nom LIKE ? OR prenom LIKE ?");
            $search_term = "%$recherche%";
            mysqli_stmt_bind_param($stmt, "ss", $search_term, $search_term);
            mysqli_stmt_execute($stmt);
            $resultat = mysqli_stmt_get_result($stmt);

            echo "<h3>Résultats pour : " . htmlspecialchars($_GET['query']) . "</h3>";

            if (mysqli_num_rows($resultat) > 0) {
                echo "<table>";
                echo "<tr><th>Nom</th><th>Prénom</th><th>Date de Naissance</th><th>Actions</th></tr>";
                
                while ($ligne = mysqli_fetch_assoc($resultat)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($ligne['nom']) . "</td>";
                    echo "<td>" . htmlspecialchars($ligne['prenom']) . "</td>";
                    echo "<td>" . htmlspecialchars($ligne['dateNaissance']) . "</td>";
                    echo "<td>";
                    echo "<a href='modifier.php?id=" . intval($ligne['id']) . "' class='action-link'>Modifier</a>";
                    echo "<a href='supprimer.php?id=" . intval($ligne['id']) . "' class='action-link delete' onclick=\"return confirm('Confirmer la suppression ?')\">Supprimer</a>"; 
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Aucun adhérent trouvé.</p>";
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($connexion);
        }
        ?>

        <div class="logout">
            <a href="logout.php">Se déconnecter</a>
        </div>
    </div>
</body>
</html>