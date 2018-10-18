<?php

namespace Tests\Unit\Billing;

use Tests\TestCase;
use App\Billing\StripePaymentGateway;

class StripePaymentGatewayTest extends TestCase
{
    private function lastCharge()
    {
        return \Stripe\Charge::all(
            ["limit" => 1],
            ['api_key' => config('services.stripe.secret')]
        )['data'][0]; 
    }

    private function newCharges($endingBefore)
    {
        return \Stripe\Charge::all(
            [
                "limit" => 1,
                "ending_before" => $endingBefore->id,
            ],
            ['api_key' => config('services.stripe.secret')]
        )['data'];
    }

    private function validToken()
    {
        return \Stripe\Token::create([
            "card" => [
                "number" => "4242424242424242",
                "exp_month" => 12,
                "exp_year" => date('Y') + 1,
                "cvc" => "123",
            ]
        ], ['api_key' => config('services.stripe.secret')])->id;
    }

    /** @test */
    function charge_with_a_valid_payment_token_are_successful()
    {
        $lastCharge = $this->lastCharge();

        // Create a new StripePaymentGateway
        $paymentGateway = new StripePaymentGateway(config('services.stripe.secret'));

        // Creata a new charge for some amount using a valid token
        $paymentGateway->charge(2500, $this->validToken());

        // Verify that the charge was completed successfully
        $this->assertCount(1, $this->newCharges($lastCharge));
        $this->assertEquals(2500, $this->lastCharge()->amount);
    }
}
