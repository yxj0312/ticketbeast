<?php

namespace Tests\Unit\Mail;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Invitation;
use App\Mail\InvitationEmail;

class InvitationEmailTest extends TestCase
{
    /** @test */
    function email_contains_a_link_to_the_invitation()
    {
        // Don't need to create, because it doesn't actually depend on the database to work
        // Make will in memory a little bit faster.
        $invitation = factory(Invitation::class)->make([
            'email' => 'john@example.com',
            'code' => 'TESTCODE1234'
        ]);

        $email = new InvitationEmail($invitation);

        $this->assertContains(url('/invitations/TESTCODE1234'), $email->render());
    }

    /** @test */
    function email_has_the_correct_subject()
    {
        $invitation = factory(Invitation::class)->make();

        $email = new InvitationEmail($invitation);

        $this->assertEquals("You're invited to join TicketBeast!", $email->build()->subject);
    }
}
