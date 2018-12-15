<?php

namespace App;

class OrderFactory
{
    public static function createForConcert($concert, $overrides = [], $ticketQuantity = 1)
    {
        $order = factory(Order::class)->create($overrides);
        $tickets = factory(Ticket::class, $ticketQuantity)->create(['concert_id' => $concert->id]);
        $order->tickets()->saveMany($tickets);
        return $order;
    }
}
