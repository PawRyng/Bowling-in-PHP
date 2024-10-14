<?php

class Game {
    public $table_of_draw;
    
    public function __construct($table_draws = []){
        $this->table_of_draw = $table_draws;
    }

    public function setPinsInLastRound($pins, $index){
        $table_of_draw = $this->table_of_draw;
        if(!empty($table_of_draw[$index])){

            $drow_count = count($table_of_draw[$index]);

            if(isset($table_of_draw[$index][$drow_count - 1]) && $table_of_draw[$index][$drow_count - 1] < 10){
                if($table_of_draw[$index][0] < 10){
                    if(array_sum($table_of_draw[$index]) === 10){
                        goto Normal;
                    }
                    else if(array_sum($table_of_draw[$index]) + $pins <= 10){
                        $this->roll($pins, $index);
                        return true;
                    }
                    else{
                        return "za duża suma ";
                    }
                }
                elseif($table_of_draw[$index][$drow_count - 1] + $pins <= 10){
                    $this->roll($pins, $index);
                    return true;                

                }
                else{
                    return "\n za duża suma w 2 rzucie! \n \n";                

                }
            }
            else{
                goto Normal;
            }
        }
        else{
            Normal:
            if($pins >=0 && $pins <= 10){
                $this->roll($pins, $index);
                return true;
            }
            else{
                return "error";
            }      
        }
    }

    // sprawdza a na końcu dodaje
    public function setPins($pins, $index) {
        if($pins <= 10 && $pins >= 0){
            if((isset($this->table_of_draw[$index][0]) && $this->table_of_draw[$index][0] + $pins <=10) || !isset($this->table_of_draw[$index][0])){
                $this->roll($pins, $index);
                return true;
            }
            else{
                return "Suma musi być mniejsza niż 10 \n \n";
            }
        }
        elseif($pins < 0){
            return "Ilość musi być większa niż 0 \n \n";
        }
        else{
            return "Ilość musi być mniejsza niż 10 \n \n";
        }
    }
    // dodawanie bil do tablicy
    private function roll($pins, $index) {
        $this->table_of_draw[$index][] = $pins;
    }

    
    // zwraca ile rzutów jest do zrobienia w danej rundzie
    public function getThrows($round) {
        $throws = 2;

        if($round ===9 && $this->isStrikeOrSprite()){
            $throws = 3;
        }

        return $throws;
    }
    private function isStrike($actual_round){
        if(!empty($this->table_of_draw[$actual_round]) && in_array(10, $this->table_of_draw[$actual_round]))  {
            return true;
        }
        else{
            return false;
        }
    }
    private function isSpare($actual_round){
        if(!empty($this->table_of_draw[$actual_round]) && array_sum($this->table_of_draw[$actual_round]) === 10) {
            return true;
        }
        else{
            return false;
        }
    }

    public function isStrikeOrSprite($actual_round = null){
        if($actual_round){
            if ($actual_round < 9 && ($this->isStrike($actual_round) || $this->isSpare($actual_round)) )  {
                return true;
            }
            else{
                return false;
            }
        }
        else{
            if(!empty($this->table_of_draw)){
                foreach($this->table_of_draw as $round){
                    if(in_array(10, $round) || array_sum($round) === 10){
                        return true;
                    }
                }
            }
            
            return false;
        }
    }
    
    public function calculateScore() {
        $score = [];

        if(!empty($this->table_of_draw)){
            foreach($this->table_of_draw as $index => $round){
                $points = 0;
                if($this->isStrike($index)){
                   $points = $this->strikePointsSum($index);
                }
                elseif($this->isSpare($index)){
                    $points = $this->sparePointsSum($index);
                }
                else{
                    $points = array_sum($round);
                }

                $round = $index + 1;
                array_push($score, $points);
            }
        }
        return $score;
    }
    public function getScore() {
        $score_array = $this->calculateScore();
        $text_to_display = "";
        $total_score = 0;
        foreach($score_array as $key => $score){
            $total_score += $score;

            $text_to_display .= "Runda ".($key+1).": ".$score."\n";
        }
        return $text_to_display."\n \n Wynik: $total_score \n \n \n";
    }

    private function strikePointsSum($index){
        $points = 10;

        // sprawdzan czy w kolejnej rundzie 
        if(isset($this->table_of_draw[$index + 1]) && $this->table_of_draw[$index + 1][0] === 10){
            $points += $this->table_of_draw[$index + 1][0];

            // sprawdza czy 2 tura istneje jak nie to sprawdza czy jest 3 tura w aktualnym dla ostatniej tury
            if(isset($this->table_of_draw[$index + 2])){
                $points += $this->table_of_draw[$index + 2][0];
            }
            elseif(isset($this->table_of_draw[$index + 1][1])){
                $points += $this->table_of_draw[$index + 1][1];
            }
        }
        else{
            // sprawdzić dla ostatniej rundy
            if(isset($this->table_of_draw[$index + 1])){
                $points += array_sum($this->table_of_draw[$index + 1]);// żle
            }
            else{
                $points = array_sum($this->table_of_draw[$index]);// żle
            }
        }
        return $points;
    }
    private function sparePointsSum($index){
        $points = 10;
        if(isset($this->table_of_draw[$index + 1][0])){
            $points += $this->table_of_draw[$index + 1][0];
        }
        // test dla 10 rundy
        elseif(isset($this->table_of_draw[$index][2])){
            $points += $this->table_of_draw[$index][2];
        }

        return $points;
    }    
}