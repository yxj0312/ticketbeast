<?php

namespace Tests\Unit\Billing;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StripePaymentGatewayTest extends TestCase
{
    /** @test */
    function charge_with_a_valid_payment_token_are_successful()
    {
        // Create a new StripePaymentGateway
        $paymentGateway = new StripePaymentGateway;

        // Creata a new charge for some amount using a valid token
        $paymentGateway->charge(2500, validToken);

        // Verify that the charge was completed successfully
        
    }
}
