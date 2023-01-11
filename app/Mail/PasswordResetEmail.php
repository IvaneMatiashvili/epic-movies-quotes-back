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

	/**
	 * Create a new message instance.
	 *
	 * @param $email
	 * @param $url
	 * @param $user
	 * @param $locale1
	 */
	public function __construct($email, $url, $user, $locale)
	{
		$this->email = $email;
		$this->url = $url;
		$this->user = $user;
		$this->locale = $locale;
	}

	/**
	 * Get the message envelope.
	 *
	 * @return \Illuminate\Mail\Mailables\Envelope
	 */
	public function envelope()
	{
		return new Envelope(
			subject: 'Password Reset Email',
		);
	}

	/**
	 * Get the message content definition.
	 *
	 * @return \Illuminate\Mail\Mailables\Content
	 */
	public function content()
	{
		return new Content(
			view: 'emails.password-reset-email',
		);
	}

	/**
	 * Get the attachments for the message.
	 *
	 * @return array
	 */
	public function attachments()
	{
		return [];
	}
}
