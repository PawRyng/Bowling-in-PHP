<?php

class Game {
    public $table_of_throw;
    
    public function __construct($table_draws = []){
        $this->table_of_throw = $table_draws;
    }

    /**
     * Sprawdza input wejścia musi być mniejszy niż 10 i większy niż 0
     * @param int $pins - Liczba przewróconych kręgli
     * @param int $index - index z tabeli w table_of_throw
     * @return  bool || string - Zwraca true gdy ilość $pins jest poprawna lub stringa z wiadomością o błędzie
     */
    private function checkDefaultInputs($pins, $index){
        if($pins >=0 && $pins <= 10){
            $this->roll($pins, $index);
            return true;
        }
        else{
            return "Podana liczba musi być większa niż o i mniejsza niż 10";
        }  
    }


    /**
     * Dodaje do tablicy rzuty z ostatniej rundy oraz waliduje wartości
     * @param int $pins - Liczba przewróconych kręgli
     * @param int $index - index z tabeli w table_of_throw
     * @return  bool || string - Zwraca true gdy ilość $pins jest poprawna oraz została dodana do tablicy lub stringa z wiadomością o błędzie
     */
    public function setPinsInLastRound($pins, $index){
        $table_of_throw = $this->table_of_throw;
        if(!empty($table_of_throw[$index])){

            $drow_count = count($table_of_throw[$index]);

            if(isset($table_of_throw[$index][$drow_count - 1]) && $table_of_throw[$index][$drow_count - 1] < 10){
                if($table_of_throw[$index][0] < 10){
                    if(array_sum($table_of_throw[$index]) === 10){
                        $this->checkDefaultInputs($pins, $index);
                    }
                    else if(array_sum($table_of_throw[$index]) + $pins <= 10){
                        $this->roll($pins, $index);
                        return true;
                    }
                    else{
                        return "Suma jest za duża";
                    }
                }
                elseif($table_of_throw[$index][$drow_count - 1] + $pins <= 10){
                    $this->roll($pins, $index);
                    return true;                

                }
                else{
                    return "\n Za duża suma w 2 rzucie! \n \n";                

                }
            }
            else{
                $this->checkDefaultInputs($pins, $index);
            }
        }
        else{
            $this->checkDefaultInputs($pins, $index);
        }
    }

     /**
     * 
     * Waliduje input a następnie dodaje do tablicy rzuty
     * @param int $pins - Liczba przewróconych kręgli
     * @param int $index - index z tabeli w table_of_throw
     * @return  bool || string - Zwraca true gdy ilość $pins jest poprawna oraz została dodana do tablicy lub stringa z wiadomością o błędzie
     */
    public function setPins($pins, $index) {
        if($pins <= 10 && $pins >= 0){
            if((isset($this->table_of_throw[$index][0]) && $this->table_of_throw[$index][0] + $pins <=10) || !isset($this->table_of_throw[$index][0])){
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


     /**
     * Dodaje do tablicy ilość przewróconych kręgli do tablicy
     * @param int $pins - Liczba przewróconych kręgli
     * @param int $index - index z tabeli w table_of_throw
     */
    private function roll($pins, $index) {
        $this->table_of_throw[$index][] = $pins;
    }

    /**
     * Zwraca ile rzutów jest do zrobienia w danej rundzie
     * @param int $round - Aktualna runda
     */
    public function getThrows($round) {
        $throws = 2;

        if($round === 9 && $this->isStrikeOrSprite()){
            $throws = 3;
        }

        return $throws;
    }

    /**
     * Sprawdza czy w aktualnej rundzie wystąpił Strike
     * @param int $actual_round - Aktualna runda
     * @return bool
     */
    private function isStrike($actual_round){
        if(!empty($this->table_of_throw[$actual_round]) && in_array(10, $this->table_of_throw[$actual_round]))  {
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Sprawdza czy w aktualnej rundzie wystąpił Spare
     * @param int $actual_round - Aktualna runda
     * @return bool
     */
    private function isSpare($actual_round){
        if(!empty($this->table_of_throw[$actual_round]) && array_sum($this->table_of_throw[$actual_round]) === 10) {
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Sprawdza czy w aktualnej rundzie wystąpił Spare lub Strike
     * @param int $actual_round - Aktualna runda
     * @return bool
     */
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
            if(!empty($this->table_of_throw)){
                foreach($this->table_of_throw as $round){
                    if(in_array(10, $round) || array_sum($round) === 10){
                        return true;
                    }
                }
            }
            
            return false;
        }
    }
    

    /**
     * Wylicza ilość punktów w danej rundzie
     * @return array - Tablica z punktami, indeks tablicy odpowiada rundzie - 1 
     */
    public function calculateScore() {
        $score = [];

        if(!empty($this->table_of_throw)){
            foreach($this->table_of_throw as $index => $round){
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

    /**
     * Wyświetla wynik całej gry oraz poszczególnych rund
     * @return string - Informacja o ilości punktów w poszczególnych rundach oraz wynik całościowy 
     */
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

    /**
     * Wylicza ile punktów jest gry w rundzie występuje strike 
     * @param int $index - index z tabeli w table_of_throw
     * @return int - Ilość punktów dla rundy która jest strike
     */
    private function strikePointsSum($index){
        $points = 10;

        // sprawdzan czy w kolejnej rundzie 
        if(isset($this->table_of_throw[$index + 1]) && $this->table_of_throw[$index + 1][0] === 10){
            $points += $this->table_of_throw[$index + 1][0];

            // sprawdza czy 2 tura istneje jak nie to sprawdza czy jest 3 tura w aktualnym dla ostatniej tury
            if(isset($this->table_of_throw[$index + 2])){
                $points += $this->table_of_throw[$index + 2][0];
            }
            elseif(isset($this->table_of_throw[$index + 1][1])){
                $points += $this->table_of_throw[$index + 1][1];
            }
        }
        else{
            // sprawdzić dla ostatniej rundy
            if(isset($this->table_of_throw[$index + 1])){
                $points += array_sum($this->table_of_throw[$index + 1]);// żle
            }
            else{
                $points = array_sum($this->table_of_throw[$index]);// żle
            }
        }
        return $points;
    }
    /**
     * Wylicza ile punktów jest gry w rundzie występuje spare 
     * @param int $index - index z tabeli w table_of_throw
     * @return int - Ilość punktów dla rundy która jest spare
     */
    private function sparePointsSum($index){
        $points = 10;
        if(isset($this->table_of_throw[$index + 1][0])){
            $points += $this->table_of_throw[$index + 1][0];
        }
        // test dla 10 rundy
        elseif(isset($this->table_of_throw[$index][2])){
            $points += $this->table_of_throw[$index][2];
        }

        return $points;
    }    
}