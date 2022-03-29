<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProcessPosterImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $concert;

    public function __construct($concert)
    {
        $this->concert = $concert;
    }

    public function handle()
    {
        $imageContents = Storage::disk('public')->get($this->concert->poster_image_path);
        // use codes from commentar,much more easier
        // https://github.com/nothingworksinc/ticketbeast/commit/3b47ce047748d538e17804ee7ed02ece8681b197#r25726881
        $image = Image::make($imageContents)->widen(600)->limitColors(255)->encode();
        Storage::disk('public')->put($this->concert->poster_image_path, (string) $image);
    }
}
