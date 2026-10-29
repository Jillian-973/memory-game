<?php
session_start();

if(!isset($_SESSION['carte'])) {
    header("Location: start.php");
    exit;
}

// on verifie que toutes les paires sont bien trouvees
foreach($_SESSION['carte'] as $key) {
    if($key["doublonTrouver"] == false) {
        header("Location: game.php");
        exit;
    }
}

// stats de fin de partie
$pairesTotal = count($_SESSION['carte']) / 2;
$erreurs = $_SESSION['nombreEssais'] - $pairesTotal;
$precision = round($pairesTotal / $_SESSION['nombreEssais'] * 100);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="barre">
    <a class="logo" href="start.php"><span class="logo-icone"></span>Memory Game</a>
</header>

<main class="panneau">
    <div class="trophee">✓</div>
    <h1>Bravo !</h1>
    <p class="sous-titre">Vous avez trouvé les <?php echo $pairesTotal; ?> paires en <?php echo $_SESSION['nombreEssais']; ?> essais.</p>

    <div class="stats">
        <div class="stat">
            <span class="stat-valeur"><?php echo $_SESSION['nombreEssais']; ?></span>
            <span class="stat-label">Essais</span>
        </div>
        <div class="stat">
            <span class="stat-valeur"><?php echo $erreurs; ?></span>
            <span class="stat-label">Erreurs</span>
        </div>
        <div class="stat">
            <span class="stat-valeur"><?php echo $precision; ?>%</span>
            <span class="stat-label">Précision</span>
        </div>
    </div>

    <div class="actions">
        <a class="bouton" href="start.php">REJOUER</a>
    </div>
</main>

</body>
</html>
