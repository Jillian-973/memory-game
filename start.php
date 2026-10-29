<?php
session_start();

$carte = [];

$valeurCartes = [
    ["id" => 1, "name" => "rune", "selectioner" => false, "doublonTrouver" => false],
    ["id" => 2, "name" => "jade", "selectioner" => false, "doublonTrouver" => false],
    ["id" => 3, "name" => "saphyre", "selectioner" => false, "doublonTrouver" => false],
    ["id" => 4, "name" => "rubis", "selectioner" => false, "doublonTrouver" => false],
    ["id" => 5, "name" => "emeraude", "selectioner" => false, "doublonTrouver" => false],
    ["id" => 6, "name" => "amaryllis", "selectioner" => false, "doublonTrouver" => false],
    ["id" => 7, "name" => "quartz", "selectioner" => false, "doublonTrouver" => false],
    ["id" => 8, "name" => "cristal", "selectioner" => false, "doublonTrouver" => false],
    ["id" => 9, "name" => "rune", "selectioner" => false, "doublonTrouver" => false],
    ["id" => 10, "name" => "jade", "selectioner" => false, "doublonTrouver" => false],
    ["id" => 11, "name" => "saphyre", "selectioner" => false, "doublonTrouver" => false],
    ["id" => 12, "name" => "rubis", "selectioner" => false, "doublonTrouver" => false],
    ["id" => 13, "name" => "emeraude", "selectioner" => false, "doublonTrouver" => false],
    ["id" => 14, "name" => "amaryllis", "selectioner" => false, "doublonTrouver" => false],
    ["id" => 15, "name" => "quartz", "selectioner" => false, "doublonTrouver" => false],
    ["id" => 16, "name" => "cristal", "selectioner" => false, "doublonTrouver" => false],
];

// nouvelle partie : on remet les cartes a zero et on les melange
$carte = $valeurCartes;
shuffle($carte);
$_SESSION['carte'] = $carte;

$_SESSION['last_clicked_card'] = null;
$_SESSION['maxCartesSelectionner'] = 0;
$_SESSION['nombreEssais'] = 0;

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

</body>
</html>
