<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\AttendeeMessageEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendAttendeeMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $attendeeMessage;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($attendeeMessage)
    {
        $this->attendeeMessage = $attendeeMessage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Image we have 2000 email adressse, then we are looping over 2000 HTTP requests
        // better way: queue up instead of send
        // One of the benefits: this job fails, it will going to a fail job table as long as we set that up
        // for example, 7 of 2000 failed, we will get the 7 failed ones in the table somewhere and we can retry these ones easily.

        $this->attendeeMessage->withChunkedRecipients(20, function ($recipients) {
            $recipients->each(function ($recipient) {
                Mail::to($recipient)->queue(new AttendeeMessageEmail($this->attendeeMessage));
            });
        });
    }
}
