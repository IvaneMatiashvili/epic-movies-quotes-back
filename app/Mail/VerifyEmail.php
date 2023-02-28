<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
	use Queueable, SerializesModels;

	public $email;

	public $url;

	public $user;

	public $locale;

	public $fromProfile;

	public function __construct($email, $url, $user, $locale, $fromProfile)
	{
		$this->email = $email;
		$this->url = $url;
		$this->user = $user;
		$this->locale = $locale;
		$this->fromProfile = $fromProfile;
	}

	public function envelope(): Envelope
	{
		return new Envelope(
			subject: 'Verify Email',
		);
	}

	public function content(): Content
	{
		return new Content(
			view: 'emails.verify-email',
		);
	}

	public function attachments(): array
	{
		return [];
	}
}
