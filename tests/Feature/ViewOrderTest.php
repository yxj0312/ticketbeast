<?php

namespace Tests\Feature;

use App\Order;
use App\Ticket;
use App\Concert;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewOrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_can_view_their_order_confirmation()
    {
        // Create an concert
        $concert = factory(Concert::class)->create(); 
        // Create an Order
        $order = factory(Order::class)->create(); 
        // Create a ticket
        $ticket = factory(Ticket::class)->create([
            'concert_id' => $concert->id,
            'order_id' => $order->id
        ]);

        // Visit the order confirmation page
        $this->get('/orders/{$order->id}');
    }
}
