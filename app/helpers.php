<?php

// the function declines the word, depending on the number n (the argument passed)
function plural($num, $form_for_1, $form_for_2, $form_for_5) {
    $num = abs($num) % 100;
    $num_x = $num % 10;
    if ($num > 10 && $num < 20) {
        return $form_for_5;
    }

    if ($num_x > 1 && $num_x < 5) {
        return $form_for_2;
    }

    if ($num_x == 1) {
        return $form_for_1;
    }

    return $form_for_5;
}

// converts a number to double format
function replacePriceToDouble($str) {
    return floatval(str_replace([',', '_'], ['.', ''], $str));
}
