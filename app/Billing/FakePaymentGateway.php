<?php

namespace App\Billing;

class FakePaymentGateway implements PaymentGateway
{
    private $charges;
    private $tokens;
    private $beforeFirstChargeCallback;

    public function __construct()
    {
        $this->charges = collect();
        $this->tokens = collect();
    }

    public function getValidTestToken($cardNumber = '4242424242424242')
    {
        $token = 'fake-tok_'.str_random(24);
        $this->tokens[$token] = $cardNumber;

        return $token;
    }

    public function charge($amount, $token)
    {
        // Check is there a callback there?
        // if so, then we have to run this callback if it set, before we do any charging
        if ($this->beforeFirstChargeCallback !== null) {
            // make the below doesn't get called again
            $callback = $this->beforeFirstChargeCallback;
            $this->beforeFirstChargeCallback = null;

            // invoke that callback to pass paymentGateway itself
            // so that callback will run before the charge happens
            // $this->beforeFirstChargeCallback->__invoke($this);
            $callback($this);
        }

        // then the rest of the charge stuffs will be excuted as expected
        if (! $this->tokens->has($token)) {
            throw new PaymentFailedException;
        }

        return $this->charges[] = new Charge([
            'amount' => $amount,
            'card_last_four' => substr($this->tokens[$token], -4),
        ]);
    }

    public function newChargesDuring($callback)
    {
        $chargeFrom = $this->charges->count();
        $callback($this);

        return $this->charges->slice($chargeFrom)->reverse()->values();
    }

    public function totalCharges()
    {
        return $this->charges->sum();
    }

    // takes a callback and store that callback in a property 'beforeFirstChargeCallback' (initalize to null)
    public function beforeFirstCharge($callback)
    {
        $this->beforeFirstChargeCallback = $callback;
    }
}
