<?php

namespace Tests\Feature;

use App\Concert;
use Tests\TestCase;
use App\Billing\PaymentGateway;
use App\Billing\FakePaymentGateway;
use Illuminate\Foundation\Testing\WithFaker;

use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseTicketsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function customer_can_purchase_concert_tickets()
    {
        $paymentGateway = new FakePaymentGateway;
        $this->app->instance(PaymentGateway::class, $paymentGateway);
        
        // Arrange
        // Create a concert
        $concert = factory(Concert::class)->create(['ticket_price' => 3250]);

        // Act
        // Purchase concert tickets
        $response = $this->json('POST',"/concerts/{$concert->id}/orders", [
            'email' => 'john@example.com',
            'ticket_quantity' => 3,
            'payment_token' => $paymentGateway->getValidTestToken(), 
        ]);

        // Assert
        $response->assertStatus(201);


        // Make sure the customer was changed the correct amount
        $this->assertEquals(9750, $paymentGateway->totalCharges());

        // Make sure that an order exists for this customer
        $order = $concert->orders()->where('email', 'john@example.com')->first();
        $response->assertNotNull($order);
        $response->assertEquals(3, $order->tickets->count());
    }
}
