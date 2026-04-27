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

if(isset($_SESSION['carte'])) {
    $carte = shuffle($_SESSION['carte']);
} else {
    $carte = $valeurCartes;
    shuffle($carte);
    $_SESSION['carte'] = $carte;
}

?>

<style>
    body {
        font-family: Arial, sans-serif;
        text-align: center;
        background-color: #f0f0f0;
        text-align: center;
        
    }
    h1 {
        color: #333;
    }
    button {
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
    }
    button:hover {
        background-color: #45a049;
    }
</style>

<h1>Memory Game</h1>
<a href="game.php"><button>START GAME</button></a>