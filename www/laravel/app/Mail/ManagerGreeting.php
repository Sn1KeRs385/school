<?php

namespace App\Mail;

use App\AdminModels\ManagementCompany;
use App\AdminModels\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ManagerGreeting extends Mailable
{
    use Queueable, SerializesModels;
    private $user;
    private $passwordd;
    private $management_сompany;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, string $passwordd, ManagementCompany $management_сompany)
    {
        $this->user = $user;
        $this->passwordd = $passwordd;
        $this->management_сompany = $management_сompany;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.manager.greeting', [
            'user' => $this->user,
            'password' => $this->passwordd,
            'management_сompany' => $this->management_сompany,
        ])
            ->to($this->user->email)
            ->subject('Добро пожаловать в Hi-Tech Dom!');
    }
}
