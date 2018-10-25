<?php

namespace App\Billing;

class FakePaymentGateway implements PaymentGateway
{
    private $charges;
    private $beforeFirstChargeCallback;

    public function __construct()
    {
        $this->charges = collect();
    }

    public function getValidTestToken()
    {
        return 'valid-token';
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
        if ($token !== $this->getValidTestToken()) {
            throw new PaymentFailedException;
        }
        $this->charges[] = $amount;
    }

    public function newChargesDuring($callback)
    {
        $chargeFrom = $this->charges->count();
        $callback($this);

        return $this->charges->slice($chargeFrom)->values();
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
