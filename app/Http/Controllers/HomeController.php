<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncomingLetter;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Notifications\SendPushNotification;
use Illuminate\Support\Facades\Notification;

class HomeController extends Controller
{
    public function dashboard()
    {
        $authenticatedEmail = Auth::user()->email;
        $notificationDisplayed = Session::get('notification_displayed');
        $unreadMessages = IncomingLetter::where('to', $authenticatedEmail)->where('is_read', false)->get();

        if (!$notificationDisplayed && $unreadMessages->count() > 0) {
            $messageGroups = [];

            foreach ($unreadMessages as $unreadMessage) {
                $messageGroups[$unreadMessage->type][] = $unreadMessage;
            }

            foreach ($messageGroups as $type => $messages) {
                $message = 'Permintaan ' . $type;
                foreach ($messages as $unreadMessage) {
                    $mahasiswaData = Mahasiswa::where('email', $unreadMessage->from)->first();
                    if ($mahasiswaData) {
                        $message .= ' oleh ' . $mahasiswaData->name;
                    }
                }
                Notification::send(Auth::user(), new SendPushNotification('New Notification', $message));
            }

            Session::put('notification_displayed', true);
        }

        return view('dashboard', ['unreadMessages' => $unreadMessages]);
    }
}
