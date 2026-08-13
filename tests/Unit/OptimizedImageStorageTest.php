<?php

namespace Tests\Unit;

use App\Services\OptimizedImageStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OptimizedImageStorageTest extends TestCase
{
    public function test_it_stores_uploaded_images_as_webp_and_limits_the_longest_edge(): void
    {
        Storage::fake('public');

        $path = app(OptimizedImageStorage::class)->store(
            UploadedFile::fake()->image('large.png', 3000, 1000),
            'test-images',
        );

        Storage::disk('public')->assertExists($path);
        $this->assertStringEndsWith('.webp', $path);

        $info = getimagesize(Storage::disk('public')->path($path));
        $this->assertSame('image/webp', $info['mime']);
        $this->assertSame(1920, $info[0]);
        $this->assertSame(640, $info[1]);
    }

    public function test_it_center_crops_gallery_covers_to_the_requested_dimensions(): void
    {
        Storage::fake('public');

        $path = app(OptimizedImageStorage::class)->store(
            UploadedFile::fake()->image('portrait.jpg', 900, 1600),
            'gallery/covers',
            1280,
            720,
        );

        $info = getimagesize(Storage::disk('public')->path($path));
        $this->assertSame('image/webp', $info['mime']);
        $this->assertSame(1280, $info[0]);
        $this->assertSame(720, $info[1]);
    }

    public function test_replace_deletes_the_previous_image_only_after_storing_the_new_one(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('cows/old.jpg', 'old');

        $path = app(OptimizedImageStorage::class)->replace(
            UploadedFile::fake()->image('new.jpg', 800, 600),
            'cows',
            'cows/old.jpg',
        );

        Storage::disk('public')->assertMissing('cows/old.jpg');
        Storage::disk('public')->assertExists($path);
    }
}
