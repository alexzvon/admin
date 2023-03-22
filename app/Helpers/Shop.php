<?php

if (!function_exists('formatPrice')) {
    function getPriceValue($value, $currencyCode): string
    {
        return \App\Models\Shop\Price::getPriceValue($value, $currencyCode);
    }
}

if (!function_exists('formatPrice')) {
    function formatPrice($value, $currencyCode): string
    {
        return \App\Models\Shop\Price::formatPrice($value, $currencyCode);
    }
}
