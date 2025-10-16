<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Meeting;

class MeetingNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $meeting;
    /**
     * Create a new message instance.
     */
    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Meeting Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
    public function build()
    {
        return $this->subject('Meeting Scheduled: ' . $this->meeting->title)
                    ->markdown('emails.meeting_notification')
                    ->with([
                        'title' => $this->meeting->title,
                        'date' => $this->meeting->date->format('Y-m-d'),
                        'time' => $this->meeting->time->format('H:i'),
                        'notes' => $this->meeting->notes,
                    ]);
    }
}
