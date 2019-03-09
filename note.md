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

