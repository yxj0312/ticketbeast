<?php

namespace Tests\Unit\Mail;

use Tests\TestCase;
use App\AttendeeMessage;
use App\Mail\AttendeeMessageEmail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendeeMessageEmailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function email_has_the_correct_subject_and_message()
    {
        $message = new AttendeeMessage([
            'subject' => 'My subject',
            'message' => 'My message',
        ]);
        $email = new AttendeeMessageEmail($message);
        $this->assertEquals("My subject", $email->build()->subject);
        $this->assertEquals("My message", trim($email->render()));
    }
}
