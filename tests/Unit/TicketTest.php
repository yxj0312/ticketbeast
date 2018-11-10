<?php

namespace Tests\Unit;

use App\Order;
use App\Ticket;
use App\Concert;
use Carbon\Carbon;
use Tests\TestCase;
use App\Facades\TicketCode;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_ticket_can_be_reserved()
    {
        $ticket = factory(Ticket::class)->create();
        $this->assertNull($ticket->reserved_at);

        $ticket->reserve();

        $this->assertNotNull($ticket->fresh()->reserved_at);
    }

    /** @test */
    function a_ticket_can_be_released()
    {
        $ticket = factory(Ticket::class)->state('reserved')->create();
        $this->assertNotNull($ticket->reserved_at);

        $ticket->release();

        $this->assertNull($ticket->fresh()->reserved_at);
    }

    /** @test */
    function a_ticket_can_be_claimed_for_an_order()
    {
        $order = factory(Order::class)->create();
        $ticket = factory(Ticket::class)->create(['code' => null]);
        TicketCode::shouldReceive('generate')->andReturn('TICKETCODE1');
        $this->assertNull($ticket->code);

        $ticket->claimFor($order);

        // Assert that the ticket is saved to the order
        $this->assertContains($ticket->id, $order->tickets->pluck('id'));
        $this->assertEquals('TICKETCODE1', $ticket->code);
        // Assert that the ticket had the expected ticket code generated

    }
}
