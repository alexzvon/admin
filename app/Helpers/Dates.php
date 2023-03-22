<?php

if (!function_exists('dateFormatFull')) {
    function dateFormatFull(Carbon\Carbon $date = null): string
    {
        return is_null($date) ? '' : $date->format('d-m-Y H:i:s');
    }
}

