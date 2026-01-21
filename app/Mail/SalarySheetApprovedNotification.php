<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\SalarySheet;

class SalarySheetApprovedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $salarySheet;
    public $approvedBy;

    /**
     * Create a new message instance.
     */
    public function __construct(SalarySheet $salarySheet, $approvedBy = null)
    {
        $this->salarySheet = $salarySheet;
        $this->approvedBy = $approvedBy;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Salary Sheet Approved - ' . $this->salarySheet->sheet_no,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.salary-sheet-approved',
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
