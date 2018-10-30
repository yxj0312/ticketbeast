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
        $this->withoutExceptionHandling();
        // Create an concert
        $concert = factory(Concert::class)->create(); 
        // Create an Order
        $order = factory(Order::class)->create([
            'confirmation_number' => 'ORDERCONFIRMATION1234'
        ]); 
        // Create a ticket
        $ticket = factory(Ticket::class)->create([
            'concert_id' => $concert->id,
            'order_id' => $order->id
        ]);

        // Visit the order confirmation page
        $response = $this->get('/orders/ORDERCONFIRMATION1234');

        $response->assertStatus(200);

        // Assert we see the correct order details
        $response->assertViewHas('order', function($viewOrder) use($order){
            return $order->id === $viewOrder->id;
        });
    }
}
