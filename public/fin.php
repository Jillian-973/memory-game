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

require __DIR__ . '/../templates/header.php';
?>

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

<?php require __DIR__ . '/../templates/footer.php'; ?>
