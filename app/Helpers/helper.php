<?php

use App\Models\IncomingLetter;

if (!function_exists('getIncomingMessage')) {
    function getIncomingMessage($email)
    {
        $count = IncomingLetter::where('to', $email)->where('is_read', false)->count();
        return $count;
    }
}
