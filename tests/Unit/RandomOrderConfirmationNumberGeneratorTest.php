<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RandomOrderConfirmationNumberGeneratorTest extends TestCase
{
    // Can only contain uppercase letters and numbers
    // Cannot contain ambiguous characters
    // Must be 24 characters long
    // All order confirmation numbers must be unique
    // ABCDEFGHJKMNPQRSTUVWXYZ
    // 23456789
}
