<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentCredentialMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $password;

    public function __construct($student, $password)
    {
        $this->student = $student;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Welcome to EDURATE PUP! Student Login Credentials')
                    ->view('emails.student_credentials'); 
    }
}