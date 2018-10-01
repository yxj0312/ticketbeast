<?php

namespace Tests\Unit;

use App\Ticket;
use Tests\TestCase;
use App\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationTest extends TestCase
{

    /** @test */
    function calculating_the_total_cost()
    {
       $tickets = collect([
           (object) ['price' => 1200],
           (object) ['price' => 1200],
           (object) ['price' => 1200],
       ]);

        $reservation = new Reservation($tickets);

        $this->assertEquals(3600, $reservation->totalCost());
    }
}
