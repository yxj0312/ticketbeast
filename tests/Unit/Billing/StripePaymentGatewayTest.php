<?php

namespace Tests\Unit\Billing;

use Tests\TestCase;
use App\Billing\StripePaymentGateway;

class StripePaymentGatewayTest extends TestCase
{
    /** @test */
    function charge_with_a_valid_payment_token_are_successful()
    {
        // Create a new StripePaymentGateway
        $paymentGateway = new StripePaymentGateway(config('services.stripe.secret'));

        $token = \Stripe\Token::create([
            "card" => [
                "number" => "4242424242424242",
                "exp_month" => 12,
                "exp_year" => date('Y') + 1,
                "cvc" => "123",
            ]
        ], ['api_key' => config('services.stripe.secret')])->id;

        // Creata a new charge for some amount using a valid token
        $paymentGateway->charge(2500, $token);

        // Verify that the charge was completed successfully
        $lastCharge = \Stripe\Charge::all(
            ["limit" => 1],
            ['api_key' => config('services.stripe.secret')]
        )['data'][0];

        $this->assertEquals(2500, $lastCharge->amount);
    }
}
