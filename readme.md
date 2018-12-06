# ticketbeast

A practice project from https://course.testdrivenlaravel.com

# Browser Testing vs. Endpoint Testing

## Brower Testing

Using a tool like Selenium or PhantomJS to simulate the user's actions inside the browser.

```php
$this->type('#title','My first blog post');
$this->type('#body','Lorem ipsum');
$this->submitForm('Add post');
```

### Pros

-   Simulates exactly how a user would interact with the application
-   Gives you complete confidence that the application is working end-to-end

### Cons

-   Need to introduce a new tool to our stack(Selenium, PhantomJS, etc) if you need to execute JavaScript
-   Slower
-   More brittle, can often break due to important changes in the UI
-   Complex to setup
-   Often can't interact with core directly, need to make assertions through the UI

## Endpoint Testing

Making HTTP requests directly to an endpoint, simulating how the browser would interact with our server instead of how the user would interact with our app.

```php
$this->post('/posts', [
    'title' => 'My first post',
    'body' => 'Lorem ipsum',
]);
```

### Pros

-   Faster
-   Doesn't require any addtional tooling
-   Interacting with more stable data structures, won't break with change are made for aesthetic reasons
-   Can interact directly with code, more flexible assertions

### Cons

-   Untested gap between front-end and back-end

## What do I want from my test?

1. Confidence that the system works
2. Reliable, don't break for unimportant reasons
3. Fast, so I run them often
4. Simple, as few tools as possible, easy to recreate test environment

# Mock the Stripe API Client vs. Integration Test Aganist Stripe

## Option 1: Mock the Stripe API Client

## Pros

-   Tests are fast
-   Tests can run without internet access

## Cons

-   Tests will pass even if we aren't using Stripe's SDK correctly
-   Tests can't be used to confirm our integration still works if Stripe makes an update to their SDK or API
-   Tests are coupled to a specific implementation; can't refactor to use Guzzle or another Stripe library

## Option 2: Integration Test Aganist Stripe

## Pros

-   Tests will fail if we use Stripe's libray incorrectly
-   Tests will fail if Stripe makes a breaking change to their SDK or API that would require us to change our code
-   Tests are still valid even if we change our implementation to use Guzzle or an unoffical Stripe package

## Cons

-   Tests are slow
-   Tests can't run without internet access

# Test traits configuration in composer.json:

```
"autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        },
        "classmap": [
            "tests"
        ]
    }
```

and don't forget to use composer dump-autoload

# Why not use Mailtrap to test mails (Chapter 14 Ep 1 - Using a Fake to Intercept Email) ?

- It makes feature tests depending on network calls to some externe servers, and that's something we try to aviod.

# Three options to publish the concert drafts (Chapter 20 Ep 1)
## Option 1: POST /concerts/{id}/publish
```
class ConcertsController
{
    // ...
}
``` 
Drawback: 

not restful

## Option 2: PATCH to /concerts/{id}

use update method
```
class ConcertsController
{
    public function update($id) {
        // ...

        if (request('published')) {
            $concert->publish();
        }
    }
}
```
Drawback: 

in the user interface, the act of editing the concert's details is disagreed the act of publishing a concert. when u click publish button, we are not passing to all the other information of updating(title, description...), all we try to say,  is to publish the concert. which also means, u need to check and validtion the inputs again, when u publish.

## Option 3: POST to /published-concerts

use create or store method of restful
```
class ConcertsController
{
    public function store()
    {
        $concert = Concert::find(request('concert_id'));

        $concert->publish();

        return redirect;
    }
}
```
