<?php namespace HashGenerator;

class HashGenerator {

    public static function generateNumber($length) {

        if ($length > 9) {
            return self::generateNumber(9) . self::generateNumber($length - 9);
        }


        $random_number = rand(0, pow(10, $length) - 1);

        return substr(str_repeat('0', $length - 1) . $random_number, -$length);

    }

    public static function generateNumberAlphabet($length) {

        $arr = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

        $hash = '';

        while ($length > 0) {
            $hash .= $arr[rand(0, 61)];
            $length--;
        }

        return $hash;

    }
}