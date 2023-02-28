<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetEmail extends Mailable
{
	use Queueable, SerializesModels;

	public $email;

	public $url;

	public $user;

	public $locale;

	public function __construct($email, $url, $user, $locale)
	{
		$this->email = $email;
		$this->url = $url;
		$this->user = $user;
		$this->locale = $locale;
	}

	public function envelope(): Envelope
	{
		return new Envelope(
			subject: 'Password Reset Email',
		);
	}

	public function content(): Content
	{
		return new Content(
			view: 'emails.password-reset-email',
		);
	}

	public function attachments(): array
	{
		return [];
	}
}
