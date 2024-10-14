<?php

require_once 'game.php';

$game = new Game();

for ($round = 0; $round <= 9; $round++) {
    echo "Runda: " . ($round + 1)."\n";
    

    $throw = 1;
    $throws = 2;
    while ( $throw <= $throws) { 
        if ($game->isStrikeOrSprite($round)) {
            $throw++;
            break;                         
        }
        if($round >= 8){
            $throws = $game->getThrows($round);
        }
        
        echo "Podaj liczbę przewróconych kręgli: ";
        $pins = fgets(STDIN);
        $pins = (int)trim($pins);
        if($round === 9){
            $setPins = $game->setPinsInLastRound($pins, $round);
        }
        else{
            $setPins = $game->setPins($pins, $round);
        }
        
        if($setPins === true){
            $throw++;
        }
        else{
            echo $setPins."testtt";
        }
    }
}
echo $game->getScore();