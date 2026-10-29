<?php
session_start();

if(isset($_GET['carte']) && isset($_SESSION['carte'])) {

    // 2 cartes deja retournees (pas une paire) : on les recache avant de retourner la nouvelle
    if($_SESSION['maxCartesSelectionner'] >= 2) {
        foreach($_SESSION['carte'] as $index => $key) {
            $_SESSION['carte'][$index]["selectioner"] = false;
        }
        $_SESSION['maxCartesSelectionner'] = 0;
    }

    // on retourne la carte cliquee
    foreach($_SESSION['carte'] as $index => $key) {
        if($key["id"] == $_GET['carte'] && $key["selectioner"] == false && $key["doublonTrouver"] == false) {
            $_SESSION['carte'][$index]["selectioner"] = true;
            $_SESSION['last_clicked_card'] = $key["id"];
            $_SESSION['maxCartesSelectionner']++;
        }
    }

    // 2 cartes retournees : on regarde si c'est une paire
    if($_SESSION['maxCartesSelectionner'] == 2) {
        $_SESSION['nombreEssais']++;

        $cartesSelectionner = [];
        foreach($_SESSION['carte'] as $index => $key) {
            if($key["selectioner"] == true) {
                $cartesSelectionner[] = $index;
            }
        }

        if($_SESSION['carte'][$cartesSelectionner[0]]["name"] == $_SESSION['carte'][$cartesSelectionner[1]]["name"]) {
            foreach($cartesSelectionner as $index) {
                $_SESSION['carte'][$index]["doublonTrouver"] = true;
                $_SESSION['carte'][$index]["selectioner"] = false;
            }
            $_SESSION['maxCartesSelectionner'] = 0;
        }
    }

    // toutes les paires sont trouvees : fin de la partie
    $doublonsTrouver = 0;
    foreach($_SESSION['carte'] as $key) {
        if($key["doublonTrouver"] == true) {
            $doublonsTrouver++;
        }
    }
    if($doublonsTrouver == count($_SESSION['carte'])) {
        header("Location: fin.php");
        exit;
    }
}

header("Location: game.php");
?>
