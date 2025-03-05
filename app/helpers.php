<?php

if (!function_exists('baht_text')) {
    /**
     * Convert a number to Thai baht text
     *
     * @param float $number
     * @return string
     */
    function baht_text($number) {
        return \App\Helpers\BahtText::convert($number);
    }
}
