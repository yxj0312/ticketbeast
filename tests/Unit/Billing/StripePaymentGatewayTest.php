<?php

namespace Tests\Unit\Billing;

use Tests\TestCase;
use App\Billing\StripePaymentGateway;
use App\Billing\PaymentFailedException;

/**
 * @group integration
 */
class StripePaymentGatewayTest extends TestCase
{
    use \PaymentGatewayContractTests;
    // vendor\bin\phpunit --exclude-group integration
    
    protected function setUp()
    {
        parent::setUp();
        $this->lastCharge = $this->lastCharge();
    }

    private function lastCharge()
    {
        return array_first(\Stripe\Charge::all(
            ["limit" => 1],
            ['api_key' => config('services.stripe.secret')]
        )['data']); 
    }

    private function newCharges()
    {
        return \Stripe\Charge::all(
            [
                "ending_before" => $this->lastCharge->id ? $this->lastCharge->id: null,
            ],
            ['api_key' => config('services.stripe.secret')]
        )['data'];
    }


    protected function getPaymentGateway()
    {
        return new StripePaymentGateway(config('services.stripe.secret'));
    }

    /** @test */
    function charges_with_an_invalid_payment_token_fail()
    {
        try {
            $paymentGateway = new StripePaymentGateway(config('services.stripe.secret'));
            $paymentGateway->charge(2500, 'invalid-payment-token');
        } catch (PaymentFailedException $e) {
            $this->assertCount(0, $this->newCharges());
            return;
        }

        $this->fail("Charging with an invalid payment token did not throw a PaymentFailedException.");
    }

    private function validToken()
    {
        return \Stripe\Token::create([
            "card" => [
                "number" => "4242424242424242",
                "exp_month" => 1,
                "exp_year" => date('Y') + 1,
                "cvc" => "123"
            ]
        ], ['api_key' => config('services.stripe.secret')])->id;
    }
}
