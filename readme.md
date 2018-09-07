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

- Simulates exactly how a user would interact with the application
- Gives you complete confidence that the application is working end-to-end

### Cons

- Need to introduce a new tool to our stack(Selenium, PhantomJS, etc) if you need to execute JavaScript
- Slower
- More brittle, can often break due to important changes in the UI
- Complex to setup
- Often can't interact with core directly, need to make assertions through the UI

## Endpoint Testing

Making HTTP requests directly to an endpoint, simulating how the browser would interact with our server instead of how the user would interact with our app.

```php
$this->post('/posts', [
    'title' => 'My first post',
    'body' => 'Lorem ipsum',
]);
```

### Pros

- Faster
- Doesn't require any addtional tooling
- Interacting with more stable data structures, won't break with change are made for aesthetic reasons
- Can interact directly with code, more flexible assertions

### Cons

- Untested gap between front-end and back-end

## What do I want from my test?

1. Confidence that the system works
2. Reliable, don't break for unimportant reasons
3. Fast, so I run them often
4. Simple, as few tools as possible, easy to recreate test environment
