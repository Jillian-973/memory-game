<?php
session_start();

// nouvelle partie : on remet les cartes a zero et on les melange
$carte = require __DIR__ . '/../src/cartes.php';
shuffle($carte);
$_SESSION['carte'] = $carte;

$_SESSION['last_clicked_card'] = null;
$_SESSION['maxCartesSelectionner'] = 0;
$_SESSION['nombreEssais'] = 0;

require __DIR__ . '/../templates/header.php';
?>

<main class="panneau">
    <h1>Memory</h1>
    <p class="sous-titre">Testez votre mémoire en retrouvant les 8 paires de pierres précieuses.</p>

    <div class="apercu">
        <div class="card">?</div>
        <div class="card selected rubis"><span class="gem"></span></div>
        <div class="card trouver saphyre"><span class="gem"></span></div>
        <div class="card">?</div>
    </div>

    <ul class="regles">
        <li><span class="regles-numero">1</span>Cliquez sur une carte pour la retourner.</li>
        <li><span class="regles-numero">2</span>Retournez-en une deuxième : si elles sont identiques, la paire est trouvée.</li>
        <li><span class="regles-numero">3</span>Trouvez toutes les paires avec le moins d'essais possible.</li>
    </ul>

    <a class="bouton" href="game.php">START GAME</a>
</main>

<?php require __DIR__ . '/../templates/footer.php'; ?>
