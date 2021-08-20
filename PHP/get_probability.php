<?php

#
#   get the probabilities of a number within ranges (With in-range stats debug + match case return)
#   Usage example: you have 3 prizes, and each have their winning chance, this function will return the matching prize based on random number within probability ranges
#
#   Quick invoke [DEBUG]: "get_probability([20,50,200], true);"
#   Quick invoke [MATCH]: "get_probability([['odd' => 20], ['odd' => 70], ['odd' => 200]], false, 'odd', true);"
#
function get_probability(array $odds = [], bool $debug_stats = false, string $key = '', bool $returnMatch = false){

    #
    #   Params description:
    #   array $odds = []            - The array that contains the probability values
    #   bool $debug_stats = false   - Should the function return a detailed debug of 500 iteration
    #   string $key = ''            - Pass only if the odds array is multi dimentional array
    #   bool $returnMatch = false   - Should the function also return the matching probability if the random number is within its range

    #
    #   The odds array must be sorted from low to high aka: 0-100
    #   Odds array's expected structure:
    #   [20, 70, 200]
    #   [['odd' => 20], ['odd' => 70], ['odd' => 200]]
    #

    $new_odds = [];
    $prev_odd = [];
    for($x=0;$x<count($odds);$x++){
        $prev_odd = ['low' => isset($prev_odd['top']) ? ($prev_odd['top'] + 1) : 0, 'top' => $prev_odd['low'] + ($key ? $odds[$x][$key] : $odds[$x])];
        $new_odds[] = $prev_odd;
    }

    if($returnMatch){
        $random = rand($new_odds[0]['low'], $new_odds[(count($new_odds)-1)]['top']);
        foreach($new_odds as $index => $odd){
            if($odd['low'] < $random && $odd['top'] >= $random){
                return ['matched_odd' => $odd, 'index' => $index];
            }
        }
    }

    if($debug_stats){
        $stats = [];
        for($x=0;$x<500;$x++){
            $random = rand($new_odds[0]['low'], $new_odds[(count($new_odds)-1)]['top']);
            foreach($new_odds as $index => $odd){
                if($odd['low'] < $random && $odd['top'] >= $random){
                    if(array_key_exists($index, $stats)){
                        $stats[$index]['odd'] = $stats[$index]['odd'] + 1;
                    }else{
                        $stats[$index] = ['type' => $odd['low'] . ' - ' . $odd['top'], 'odd' => 1];
                    }

                    echo 'The random number "'. $random .'" is within the range of: "'. $odd['low'] . ' - ' . $odd['top'] .'"<br/>';
                }
            }
        }
        return json_encode($stats);
    }
    return $new_odds;
}
