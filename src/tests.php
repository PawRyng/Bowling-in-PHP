<?php
require_once "game.php";


class Tests {
    private $default_games = [
        [
            'table' => [
                [10],  // Frame 1: Strike (10 + 10 + 10 = 30)
                [10],  // Frame 2: Strike (10 + 10 + 10 = 30)
                [10],  // Frame 3: Strike (10 + 10 + 10 = 30)
                [10],  // Frame 4: Strike (10 + 10 + 10 = 30)
                [10],  // Frame 5: Strike (10 + 10 + 10 = 30)
                [10],  // Frame 6: Strike (10 + 10 + 10 = 30)
                [10],  // Frame 7: Strike (10 + 10 + 10 = 30)
                [10],  // Frame 8: Strike (10 + 10 + 10 = 30)
                [10],  // Frame 9: Strike (10 + 10 + 10 = 30)
                [10, 10, 5]  // Frame 10: Strike (10 + 10 + 5 = 25)
            ],
            'results' => [
                30, 30, 30, 30, 30, 30, 30, 30, 30, 25 
            ]
        ],
        [
            'table' => [
                [10],  // Frame 1: Strike (10 + 10 + 10 = 30)
                [10],  // Frame 2: Strike (10 + 10 + 10 = 30)
                [10],  // Frame 3: Strike (10 + 10 + 10 = 30)
                [10],  // Frame 4: Strike (10 + 10 + 10 = 30)
                [10],  // Frame 5: Strike (10 + 10 + 10 = 30)
                [10],  // Frame 6: Strike (10 + 10 + 10 = 30)
                [10],  // Frame 7: Strike (10 + 10 + 10 = 30)
                [10],  // Frame 8: Strike (10 + 10 + 10 = 30)
                [10],  // Frame 9: Strike (10 + 10 + 5 = 25)
                [10, 10, 5]  // Frame 10: Strike (10 + 10 + 5 = 25)
            ],
            'results' => [
                30, 30, 30, 30, 30, 30, 30, 30, 30, 25
            ]
        ],
        [
            'table' => [
                [5, 5],  // Frame 1: Spare (10 + 7 = 17)
                [7, 3],  // Frame 2: Spare (10 + 4 = 14)
                [4, 6],  // Frame 3: Spare (10 + 9 = 19)
                [9, 1],  // Frame 4: Spare (10 + 3 = 13)
                [3, 7],  // Frame 5: Spare (10 + 8 = 18)
                [8, 2],  // Frame 6: Spare (10 + 6 = 16)
                [6, 4],  // Frame 7: Spare (10 + 2 = 12)
                [2, 8],  // Frame 8: Spare (10 + 7 = 17)
                [7, 3],  // Frame 9: Spare (10 + 5 = 15)
                [5, 5, 8]  // Frame 10: Spare (10 + 8 = 18)
            ],
            'results' => [
                17, 14, 19, 13, 18, 16, 12, 17, 15, 18
            ]
        ],
        [
            'table' => [
                [10],  // Frame 1: Strike (10 + 7 + 3 = 20)
                [7, 3],  // Frame 2: Spare (10 + 8 = 18)
                [8, 2],  // Frame 3: Spare (10 + 10 = 20)
                [10],  // Frame 4: Strike (10 + 6 + 3 = 19)
                [6, 3],  // Frame 5: (6 + 3 = 9)
                [9, 1],  // Frame 6: Spare (10 + 7 = 17)
                [7, 2],  // Frame 7: (7 + 2 = 9)
                [8, 1],  // Frame 8: (8 + 1 = 9)
                [10],  // Frame 9: Strike (10 + 10 + 7 = 27)
                [10, 7, 3]  // Frame 10: Strike (10 + 7 + 3 = 20)
            ],
            'results' => [
                20, 18, 20, 19, 9, 17, 9, 9, 27, 20
            ]
        ],
        [
            'table' => [
                [9, 1],  // Frame 1: Spare (10 + 5 = 15)
                [5, 5],  // Frame 2: Spare (10 + 10 = 20)
                [10],    // Frame 3: Strike (10 + 3 + 6 = 19)
                [3, 6],  // Frame 4: (3 + 6 = 9)
                [7, 3],  // Frame 5: Spare (10 + 10 = 20)
                [10],    // Frame 6: Strike (10 + 10 + 5 = 25)
                [10],    // Frame 7: Strike (10 + 5 + 4 = 19)
                [5, 4],  // Frame 8: (5 + 4 = 9)
                [9, 1],  // Frame 9: Spare (10 + 10 = 20)
                [10, 10, 10]  // Frame 10: Strike (10 + 10 + 10 = 30)
            ],
            'results' => [
                15, 20, 19, 9, 20, 25, 19, 9, 20, 30
            ]
        ]
    ];


    public $games;

    public function __construct($games_array = null) {
        if ($games_array === null) {
            $this->games = $this->default_games;
        } else {
            $this->games = $games_array;
        }
    }
    
    /**
     * Sprawdza wszystkie testy dodane w konstruktorze albo domyślne
     * @return string
     */
    public function allTest() {
        $display_text = "";
        foreach($this->games as $round_key => $game) {
            $game_class = new Game($game['table']);

            $output = $game_class->calculateScore();

            $error = "";

            foreach($output as $key => $output_score) {
                if($output_score !== $game['results'][$key]) {
                    $error .= "Test $round_key błąd w rundzie $key\n";
                }
            }

            if(empty($error)) {
                $display_text .= "Test $round_key >> ok \n";
            } else {
                $display_text .= $error;
            }
        }

        return $display_text;
    }

    /**
     * Sprawdza pojedyńczy test z tabeli games
     * @param int $id - index gry w tabeli games
     * @return string
     */
    public function singleTest($id) {
        $output_text = "";
        $game_class = new Game($this->games[$id]['table']);
        $output = $game_class->calculateScore();
        foreach($output as $key => $output_score) {
            if($output_score !== $this->games[$id]['results'][$key]) {
                $expectation =  $this->games[$id]['results'][$key];
                $output_text .= "Runda $key jest $output_score oczekuje $expectation\n";
            }
        }

        if(empty($output_text)){
            $output_text .= "Test $id OK!\n";
        }
        return $output_text;
    }
}

$test = new Tests();
echo $test->singleTest(2);