<?php

namespace App\Helper;
class Helper{
    public static function all(){
        return [
            'Lion' => true,
            'Tiger' => true,
            'Bear' => true,
            'Elephant' => false,
            'Giraffe' => false,
            'Zebra' => false
        ];
            
    }
    public static function isPredator(string $animal): bool{
        $predators = ['Lion', 'Tiger', 'Bear'];
        return in_array($animal, $predators);
    }

    
}