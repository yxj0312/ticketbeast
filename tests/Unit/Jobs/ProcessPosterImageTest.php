<?php

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use App\ConcertFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Jobs\ProcessPosterImage;

class ProcessPosterImageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_resizes_the_poster_image_to_600px_wide()
    {
        Storage::fake();
        Storage::disk('public')->put(
            'posters/example-poster.png',
            file_get_contents(base_path('tests/__fixtures__/full-size-poster.png'))
        );
        $concert = ConcertFactory::createPublished([
            'poster_image_path' => 'posters/example-poster.png'
        ]);

        ProcessPosterImage::dispatch($concert);

        $resizeImage = Storage::disk('public')->get('posters/example-poster.png');
        list($width) = getimagesizefromstring($resizeImage);
        $this->assertEquals(600, $width);
    }
}
