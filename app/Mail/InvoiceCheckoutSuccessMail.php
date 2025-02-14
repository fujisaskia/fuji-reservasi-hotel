<?php

namespace App\Mail;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceCheckoutSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $room;
    public $reservation;
    public $roomPaymentTotal;
    public $serviceOrderTotal;
    public $grandTotal;
    public $nights;
    public $deposit;
    public $remainingDeposit;
    public $additionalPaymentRequired;
    public $roomPricePerNight;

    public function __construct($room, $reservation, $roomPaymentTotal, $serviceOrderTotal, $grandTotal, $nights, $deposit, $remainingDeposit, $additionalPaymentRequired, $roomPricePerNight)
    {
        $this->room = $room;
        $this->reservation = $reservation;
        $this->roomPaymentTotal = $roomPaymentTotal;
        $this->serviceOrderTotal = $serviceOrderTotal;
        $this->grandTotal = $grandTotal;
        $this->nights = $nights;
        $this->deposit = $deposit;
        $this->remainingDeposit = $remainingDeposit;
        $this->additionalPaymentRequired = $additionalPaymentRequired;
        $this->roomPricePerNight = $roomPricePerNight;
    }

    public function build()
    {
        return $this->subject('Invoice Rincian Pembayaran Reservasi')
                    ->view('mail.invoice');
    }
}

