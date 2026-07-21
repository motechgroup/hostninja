<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Models\User;
use App\Services\MailConfigService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoicePaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public Invoice $invoice;
    public User $user;

    public function __construct(Invoice $invoice, User $user)
    {
        MailConfigService::applyDynamicSmtpConfig();
        $this->invoice = $invoice;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject("Payment Receipt: Invoice #{$this->invoice->invoice_number}")
                    ->view('emails.invoice_paid');
    }
}
