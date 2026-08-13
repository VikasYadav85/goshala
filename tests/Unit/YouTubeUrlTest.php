<?php

namespace Tests\Unit;

use App\Rules\YouTubeUrl;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class YouTubeUrlTest extends TestCase
{
    #[DataProvider('validUrls')]
    public function test_it_accepts_supported_youtube_urls(string $url): void
    {
        $validator = Validator::make(['url' => $url], ['url' => [new YouTubeUrl]]);

        $this->assertTrue($validator->passes());
    }

    #[DataProvider('invalidUrls')]
    public function test_it_rejects_non_youtube_and_malformed_urls(string $url): void
    {
        $validator = Validator::make(['url' => $url], ['url' => [new YouTubeUrl]]);

        $this->assertTrue($validator->fails());
    }

    public static function validUrls(): array
    {
        return [
            ['https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
            ['https://youtu.be/dQw4w9WgXcQ?t=30'],
            ['https://youtube.com/shorts/dQw4w9WgXcQ'],
            ['https://youtube.com/live/dQw4w9WgXcQ?feature=share'],
            ['https://www.youtube.com/embed/dQw4w9WgXcQ'],
        ];
    }

    public static function invalidUrls(): array
    {
        return [
            ['https://vimeo.com/123456'],
            ['https://youtube.com/watch?v=too-short'],
            ['javascript:alert(1)'],
            ['youtube.com/watch?v=dQw4w9WgXcQ'],
        ];
    }
}
