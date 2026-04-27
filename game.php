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
    .exit-button {
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
    }
    .exit-button:hover {
        background-color: #45a049;
    }

    .game-board{
        display: grid;
        justify-content: center;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin: 20px auto;
        max-width: 1000px;

    }
    .card{
        padding: 70px 0px;
        font-size: 16px;
        cursor: pointer;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        text-decoration: none;
    }

    .selected{
        background-color: #275f2a;
    }
</style>


<div class="game">
    <h1>Memory Game</h1>

    <div class="game-board">
    
    <?php
    session_start();
    if($_SESSION != null){
        foreach($_SESSION['carte'] as $key ){
            

            if(isset($_SESSION['last_clicked_card']) && $_SESSION['last_clicked_card'] == $key["id"]){
                $key["selectioner"] = true;
            }
            if($key["selectioner"] == true){
                echo "<a class='card selected' href='#'>" . $key["name"] . "</a>";
            }
            else
                echo "<a class='card' href='click.php?carte=" . $key["id"] . "'>" . $key["id"] . "</a>";
            

        }
        echo $_SESSION['last_clicked_card'];
        echo $_SESSION['maxCartesSelectionner'];
    }
    else{
        echo "<p>Aucune carte disponible.</p>";
    }

    
    ?>
    </div>

    <div>
        <a href="start.php"><button class="exit-button">EXIT GAME</button></a>
    </div>
</div>

<?php
    
?>
