<?php

if (!function_exists('displayCheckIfTrue')) {
    function displayCheckIfTrue($value)
    {
        return $value == 1 ? '<i class="las la-check la-lg"></i>' : null;
    }
}