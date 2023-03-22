<?php

if (!function_exists('camelize')) {
    function camelize(string $string): string
    {
        return str_replace(' ', '', ucwords(preg_replace("/[\-\_]/", ' ', $string)));
    }
}

if (!function_exists('encodePassword')) {
    function encodePassword(string $password): string
    {
        return \Hash::make($password);
    }
}


