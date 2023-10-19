<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    private $data;
    private $invoice;

    /**
     * Create a new message instance.
     *
     * @param $data
     * @param $invoice
     */
    public function __construct($data, $invoice)
    {
        $this->data = $data;
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.new-order', ['data' => $this->data])
            ->subject("Your Peaks Order (#{$this->data->order_no}) is Confirmed")
            ->attach(getFilePath($this->data->invoice), [
                'as' => 'invoice.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
