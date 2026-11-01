<?php
session_start();

if(!isset($_SESSION['carte'])) {
    header("Location: start.php");
    exit;
}

// on calcule les stats a afficher
$pairesTrouver = 0;
$dernierTrouver = false;
foreach($_SESSION['carte'] as $key) {
    if($key["doublonTrouver"] == true) {
        $pairesTrouver++;
        if($key["id"] == $_SESSION['last_clicked_card']) {
            $dernierTrouver = true;
        }
    }
}
$pairesTrouver = $pairesTrouver / 2;
$pairesTotal = count($_SESSION['carte']) / 2;
$erreurs = $_SESSION['nombreEssais'] - $pairesTrouver;

// message affiche au joueur selon ce qui vient de se passer
if($_SESSION['maxCartesSelectionner'] == 2) {
    $message = "Raté ! Cliquez sur une carte pour continuer.";
    $classeMessage = "message-rate";
}
else if($_SESSION['maxCartesSelectionner'] == 1) {
    $message = "Trouvez la carte jumelle !";
    $classeMessage = "";
}
else if($dernierTrouver == true) {
    $message = "Bien joué, une paire de plus !";
    $classeMessage = "message-bravo";
}
else {
    $message = "Retournez deux cartes pour trouver une paire.";
    $classeMessage = "";
}

require __DIR__ . '/../templates/header.php';
?>

<main class="panneau game">
    <h1>Memory</h1>

    <div class="stats">
        <div class="stat">
            <span class="stat-valeur"><?php echo $_SESSION['nombreEssais']; ?></span>
            <span class="stat-label">Essais</span>
        </div>
        <div class="stat">
            <span class="stat-valeur"><?php echo $pairesTrouver . " / " . $pairesTotal; ?></span>
            <span class="stat-label">Paires</span>
        </div>
        <div class="stat">
            <span class="stat-valeur"><?php echo $erreurs; ?></span>
            <span class="stat-label">Erreurs</span>
        </div>
    </div>

    <div class="progression">
        <div class="progression-remplie" style="width: <?php echo $pairesTrouver / $pairesTotal * 100; ?>%"></div>
    </div>

    <p class="message <?php echo $classeMessage; ?>"><?php echo $message; ?></p>

    <div class="game-board">
    <?php
    foreach($_SESSION['carte'] as $key ){

        // la derniere carte cliquee joue l'animation de retournement
        $anime = "";
        if($key["id"] == $_SESSION['last_clicked_card']){
            $anime = " anime";
        }

        if($key["doublonTrouver"] == true){
            echo "<div class='card trouver " . $key["name"] . $anime . "'><span class='gem'></span><span class='nom'>" . $key["name"] . "</span></div>";
        }
        else if($key["selectioner"] == true){
            // 2 cartes retournees qui ne vont pas ensemble : on les marque en rouge
            $rate = "";
            if($_SESSION['maxCartesSelectionner'] == 2){
                $rate = " rate";
            }
            echo "<div class='card selected " . $key["name"] . $rate . $anime . "'><span class='gem'></span><span class='nom'>" . $key["name"] . "</span></div>";
        }
        else
            echo "<a class='card' href='click.php?carte=" . $key["id"] . "'>?</a>";

    }
    ?>
    </div>

    <div class="actions">
        <a class="bouton bouton-secondaire" href="start.php">EXIT GAME</a>
    </div>
</main>

<?php require __DIR__ . '/../templates/footer.php'; ?>
