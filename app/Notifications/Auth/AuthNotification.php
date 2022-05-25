<?php

namespace App\Notifications\Auth;

use App\Mail\Auth\AuthEmail;
use App\Models\AuthToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AuthNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $authToken;

    public $mailData;

    public function __construct(AuthToken $authToken)
    {
        $this->authToken = $authToken;
        $this->setMailData();
    }

    public function setMailData()
    {
        switch ($this->authToken->type) {
            case AuthToken::PASSWORD_RESET_TOKEN:
                $this->mailData = [
                    'message' => 'Please click on the link below to reset your password.',
                    'url' => config('app.frontend_app_url') . '/reset-password/' . $this->authToken->token,
                    'cta' => 'Reset Password',
                    'greeting' => 'Hello,',
                    'subject' => 'Reset Password'
                ];
                break;
            case AuthToken::VERIFY_EMAIL_TOKEN:
                $this->mailData = [
                    'message' => 'Please verify your email before proceeding further.',
                    'url' => config('app.frontend_app_url') . '/verify-email/' . $this->authToken->token,
                    'cta' => 'Verify Now',
                    'greeting' => 'Welcome,',
                    'subject' => 'Verify Email'
                ];
                break;
        }
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting($this->mailData['greeting'])
            ->subject($this->mailData['subject'])
            ->line($this->mailData['message'])
            ->action($this->mailData['cta'], $this->mailData['url'])
            ->line('Thank you for using our application!');
    }

    public function shouldSend()
    {
        return isset($this->mailData['message'])
            && isset($this->mailData['url'])
            && isset($this->mailData['cta'])
            && isset($this->mailData['greeting'])
            && isset($this->mailData['subject']);
    }
}