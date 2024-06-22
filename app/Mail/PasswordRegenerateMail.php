<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class PasswordRegenerateMail extends Mailable {
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected string $name,
        protected string $password,
    ) {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        return new Envelope(subject: 'Пожалуйста обновите пароль на странице вашего профиля');
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {
        return new Content(
            markdown: 'emails.oauth2-user-registered',
            with: [
                'title'      => "Здравствуйте ".$this->name
                                .', пожалуйста обновите пароль на странице вашего профиля',
                'password'   => $this->password,
                'actionUrl'  => route('profile.show'),
                'actionText' => 'Перейти на страницу профиля',
            ],
        );
    }
}
