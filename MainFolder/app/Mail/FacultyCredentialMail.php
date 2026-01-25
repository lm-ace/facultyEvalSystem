<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FacultyCredentialMail extends Mailable
{
    use Queueable, SerializesModels;

    public $faculty;
    public $password;

    public function __construct($faculty, $password)
    {
        $this->faculty = $faculty;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Welcome to EDURATE PUP! Faculty Login Credentials')
                    ->view('emails.faculty_credentials');
    }
}