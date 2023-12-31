<?php

namespace Mtsung\JoymapCore\Mail\Order;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Mtsung\JoymapCore\Enums\OrderMailTypeEnum;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\Store;

abstract class OrderAbstract extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public OrderMailTypeEnum $type;
    public Order $order;
    public Store $store;
    public Carbon $reservationDatetime;

    public function __construct(Order $order, OrderMailTypeEnum $type)
    {
        $this->type = $type;
        $this->order = $order;
        $this->store = $order->store;
        $this->reservationDatetime = $order->reservation_datetime;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address'),
                $this->store->name,
            ),
            subject: __('joymap::mail.order.subject.' . $this->type->value, [
                'name' => $this->store->name,
                'people_count' => (int)$this->order->adult_num + (int)$this->order->child_num,
                'date' => $this->reservationDatetime->format('m/d'),
                'time' => $this->reservationDatetime->format('H:i'),
                'week' => __('joymap::week.abbr.' . $this->reservationDatetime->dayOfWeek),
            ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'joymap::order.' . $this->type->value,
        );
    }
}
