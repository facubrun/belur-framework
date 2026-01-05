<?php

function snake_case(string $str): string {
    $snake_cased = [];
    $skip = [' ', '-', '.', '/', ';', ':', '\\', '.', ',', '_'];

    $i = 0;
    while ($i < strlen($str)) {
        $last = count($snake_cased) > 0
            ? $snake_cased[count($snake_cased) - 1]
            : null;
        $char = $str[$i++];
        if (ctype_upper($char)) {
            if ($last !== '_') {
                $snake_cased[] = '_';
            }
            $snake_cased[] = strtolower($char);
        } elseif (ctype_lower($char)) {
            $snake_cased[] = $char;
        } elseif (in_array($char, $skip)) {
            if ($last !== '_') {
                $snake_cased[] = '_';
            }
            while ($i < strlen($str) && in_array($str[$i], $skip)) {
                $i++;
            }
        }
    }

    if ($snake_cased[0] == '_') {
        $snake_cased[0] = '';
    }
    if ($snake_cased[count($snake_cased) - 1] == '_') {
        $snake_cased[count($snake_cased) - 1] = '';
    }

    return implode('', $snake_cased);
}
