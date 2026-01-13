<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CredentialsMail extends Mailable
{
    // Inalis ang Queueable para mag-send agad (Direct Send)
    use SerializesModels;

    public $user;
    public $password;
    public $userType;
    public $loginUrl;

    public function __construct(User $user, $password, $userType = 'user')
    {
        $this->user = $user;
        $this->password = $password;
        $this->userType = $userType;
        $this->loginUrl = url('/login');
    }

    public function build()
    {
        $subject = 'Your ' . ($this->userType === 'faculty' ? 'Faculty' : 'Student') . ' Account Credentials - EduRate System';
        
        return $this->subject($subject)
            ->markdown('emails.credentials')
            ->with([
                'name' => $this->user->name,
                'email' => $this->user->email,
                'password' => $this->password,
                'userType' => $this->userType,
                'userId' => $this->user->user_id,
                'loginUrl' => $this->loginUrl,
            ]);
    }
}