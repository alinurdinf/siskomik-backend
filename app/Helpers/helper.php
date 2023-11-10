<?php

use App\Models\IncomingLetter;

if (!function_exists('getIncomingMessage')) {
    function getIncomingMessage($email)
    {
        $count = IncomingLetter::where('to', $email)->where('is_read', false)->count();
        return $count;
    }
}

if (!function_exists('getStatus')) {
    function getStatus($code)
    {
        switch ($code) {
            case 'VALIDATE':
                $status = 'Surat telah divalidasi, dan segera diproses';
                break;

            case 'APPROVE':
                $status = 'Permintaan surat telah diproses dan disetujui';
                break;

            case 'REJECT':
                $status = 'Permintaan surat ditolak, silahkan hubungi Akademik';
                break;

            case 'SENT':
                $status = 'Permintaan telah dikirimkan ke Akademik';
                break;
        }

        return $status;
    }
}
