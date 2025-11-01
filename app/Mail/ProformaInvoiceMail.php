<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\SourcingOrder;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class ProformaInvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $sourcingOrder;
    // ❌ REMOVED: public $pdf; - Don't store the PDF as a property

    public $tries = 3;
    public $backoff = [60, 300, 900]; // 1min, 5min, 15min

    /**
     * Create a new message instance.
     * 
     * ✅ Only accept and store the SourcingOrder model
     */
    public function __construct(SourcingOrder $sourcingOrder)
    {
        $this->sourcingOrder = $sourcingOrder;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pro-forma Invoice for Your Order #' . $this->sourcingOrder->id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.proforma-invoice',
            with: [
                'order' => $this->sourcingOrder,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * ✅ Generate PDF HERE, not in the constructor
     * 
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        Log::debug('ProformaInvoiceMail attachments method called for Sourcing Order #' . $this->sourcingOrder->id);
        
        // Generate the PDF when attachments are requested (after deserialization)
        $pdf = $this->generateProformaInvoicePDF();
        
        return [
            Attachment::fromData(
                fn () => $pdf->output(), 
                'proforma-invoice-' . $this->sourcingOrder->id . '.pdf'
            )->withMime('application/pdf'),
        ];
    }

    /**
     * Generate the pro-forma invoice PDF
     * 
     * ✅ This method runs AFTER the job is dequeued
     */
    private function generateProformaInvoicePDF()
    {
        Log::debug('Generating PDF for Sourcing Order #' . $this->sourcingOrder->id);
        
        // Load necessary relationships
        $this->sourcingOrder->load([
            'user',
            'quotation.sourcingRequest.destinations',
        ]);

        // Generate the PDF from your view
        $pdf = PDF::loadView('pdf.proforma-invoice', [
            'sourcingOrder' => $this->sourcingOrder,
            'generatedDate' => now(),
        ]);

        // Set paper size and orientation
        $pdf->setPaper('a4', 'portrait');

        return $pdf;
    }

    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil(): \DateTime
    {
        return now()->addHours(24);
    }
}