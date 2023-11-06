<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LetterApproval extends Mailable
{
    use Queueable, SerializesModels;
    public $maildata;
    /**
     * Create a new message instance.
     */
    public function __construct($maildata)
    {
        $this->maildata = $maildata;
    }
    public function build()
    {
        $name = $this->maildata->name;
        $subject = "Pengajuan " . $this->maildata->type . " Dari " . $this->maildata->name;
        $attachmentPath = $this->attachment($this->maildata->file_path);
        $mail = $this->subject($subject)
            ->markdown('mailable.approval')
            ->with('maildata', $this->maildata)
            ->attach($attachmentPath);

        return $mail;
    }

    public function attachment($file_path)
    {
        $path = 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $file_path;
        return storage_path($path);
    }
}
