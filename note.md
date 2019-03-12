# Payment

## related courses:
1. [Chapter 2 Ep 3 Outlining the First Purchasing Test](https://course.testdrivenlaravel.com/lessons/module-2/outlining-the-first-purchasing-test#11)
    - [commit](https://github.com/yxj0312/ticketbeast/commit/cab02d220007eb392c338f52e4ff2c66ddb29997)

    - Thinking of first purchasing test:
        > - Arrange: Create a concert
        > - Act: Purchase concert tickets
        > - Assert: Make sure the customer was charged the correct amount, make sure that an order exists for this customer

    - Purchase concert tickets:

        > We get the payment token from Stripe as well as the Email address of the customer and the number of tickets they wanna purchase, and we gonna send them back to the server and kick off the process that purchase those tickets.

        ```php
            'email' => 'john@example.com',
            'ticket_quantity' => 3,
            'payment_token' => $this->paymentGateway->getValidTestToken()
        ```

        - Where we get a 'valid' token from?
            > Stripe test enviroment can give us a valid payment token, if we pass along a test card that Stripe knows

2. [Chapter 2 Ep 4 Faking the Payment Gateway](https://course.testdrivenlaravel.com/lessons/module-2/faking-the-payment-gateway?autoplay=true#12)

   - [commit](https://github.com/yxj0312/ticketbeast/commit/16e9745ff17f38f3ba89ab804e579301fa423b71)

   - Variable of paymentGateway gonna be:
       
       I. Real paymentGateway: Test depends being on the stripe, must online.

       II. Fake paymentGateway: works locally. but behavior like stripe Gateway
    
    - [Structure of FakePaymentGateway](https://github.com/yxj0312/ticketbeast/blob/16e9745ff17f38f3ba89ab804e579301fa423b71/app/Billing/FakePaymentGateway.php)

        I. [Charges_with_a_valid_payment_token_are_successful](https://github.com/yxj0312/ticketbeast/blob/16e9745ff17f38f3ba89ab804e579301fa423b71/tests/Unit/Billing/FakePaymentGatewayTest.php)
        ```php
            /** @test */
            function charges_with_a_valid_payment_token_are_successful()
            {
                $paymentGateway = new FakePaymentGateway;
                
                $paymentGateway->charge(2500, $paymentGateway->getValidTestToken());
                
                $this->assertEquals(2500, $paymentGateway->totalCharges());
            }        
        ```
        II. 'Charges' as a __construct:

        ```php
            private $charges;
        
            public function __construct()
            {
                $this->charges = collect();   
            }
        ```
        III. 'Charge' method: first just save all the amount into charges (check the validation of the token not yet)

        ```php
            public function charge($amount, $token)
            {
                $this->charges[] = $amount;
            }
        ```

    - PaymentGateway interface

        - Bind to the container, avoid 'BindingResolutionException'

        in customer_can_purchase_concert_tickets()
        ```php
            // Whenever the system needs a paymentgateway interface, supply our $paymentGateway here.
            $this->app->instance(PaymentGateway::class, $paymentGateway);
        ```

        - Where can we get the 'amount' from?
        in store method of ConcertOrdersController
        ```php
            $concert = Concert::find($concertId);
            $ticketQuantity = request('ticket_quantity');
            $amount = $ticketQuantity * $concert->ticket_price;
        ```

        - Where can we get the token?
        in store method of ConcertOrdersController
        ```php
            $token = request('payment_token')
        ```

3.  Chapter 2 Ep 5 Adding Tickets to Orders
    - Create order model
    - Save concert_id and customer email to it
    - Create ticket model
    - In the ConcertsOrderController store the orders'tikets with request('ticket_quanitity')

4. [Chapter 2 Ep 9 Handling Failed Charges](https://course.testdrivenlaravel.com/lessons/module-2/handling-failed-charges#17)

    - [Commit](https://github.com/yxj0312/ticketbeast/commit/f5630d9085ba7cd65acc2539e5bb8fe4ef7d0b40)

    - When there is a invalid payment token, throw an exception called PaymentFailedException, and return 422 statuts
        
        - Unit Test
        ```php
            /** @test */
            function charges_with_an_invalid_payment_token_fail()
            {
                try {
                    $paymentGateway = new FakePaymentGateway;
                    $paymentGateway->charge(2500, 'invalid-payment-token');
                } catch(PaymentFailedException $e) {
                    return;
                }
                $this->fail();
            }
        ``` 

        - Method

        ```php
            public function charge($amount, $token)
            {
                if ($token !== $this->getValidTestToken()) {
                    throw new PaymentFailedException;
                }
                $this->charges[] = $amount;
            }
            

            <?php
            namespace App\Billing;
            
            Class PaymentFailedException extends \RuntimeException
            {
            }
        ```

