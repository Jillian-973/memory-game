<?php
$maxCartesSelectionner = 0;

session_start();

if(isset($_GET['carte'])) {
    if($_SESSION['maxCartesSelectionner'] < 2) {
        $_SESSION['last_clicked_card'] = $_GET['carte'];
        $maxCartesSelectionner++;
        $_SESSION['maxCartesSelectionner'] = $maxCartesSelectionner;
        header("Location: game.php");
        }
    else{
        $maxCartesSelectionner = 0;
        $_SESSION['maxCartesSelectionner'] = $maxCartesSelectionner;
        header("Location: game.php");
    }
}
?>