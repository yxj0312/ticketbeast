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
    // vendor\bin\phpunit --exclude-group integration
    
    protected function setUp()
    {
        parent::setUp();
        $this->lastCharge = $this->lastCharge();
    }

    private function lastCharge()
    {
        return \Stripe\Charge::all(
            ["limit" => 1],
            ['api_key' => config('services.stripe.secret')]
        )['data'][0]; 
    }

    private function newCharges()
    {
        return \Stripe\Charge::all(
            [
                "limit" => 1,
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
    function charge_with_a_valid_payment_token_are_successful()
    {
        // Create a new StripePaymentGateway
        $paymentGateway = $this->getPaymentGateway();

        // Creata a new charge for some amount using a valid token
        // $paymentGateway->charge(2500, $paymentGateway->getValidTestToken());
        $newCharges = $paymentGateway->newChargesDuring(function ($paymentGateway){
            $paymentGateway->charge(2500, $paymentGateway->getValidTestToken());
        });

        // Verify that the charge was completed successfully
        $this->assertCount(1, $newCharges);
        $this->assertEquals(2500, $newCharges->sum());
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
}
