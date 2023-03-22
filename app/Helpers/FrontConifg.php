<?php

if (!function_exists('getFrontConfig')) {
    function getFrontConfig(): string
    {
        return \App\Http\Controllers\FrontConfigController::getConfig();
    }
}

