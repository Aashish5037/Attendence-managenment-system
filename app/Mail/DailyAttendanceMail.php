<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyAttendanceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    public $attendance;
    public $payroll;

    /**
     * Create a new message instance.
     */
    public function __construct($employee, $attendance, $payroll = null)
    {
        $this->employee = $employee;
        $this->attendance = $attendance;
        $this->payroll = $payroll;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daily Attendance Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.attendance.daily',
            with: [
                'employee' => $this->employee,
                'attendance' => $this->attendance,
                'payroll' => $this->payroll,
            ]
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
}
