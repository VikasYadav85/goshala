<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class OptimizedImageStorage
{
    private const WEBP_QUALITY = 82;

    private const MAX_DIMENSION = 1920;

    public function store(
        UploadedFile $upload,
        string $directory,
        ?int $targetWidth = null,
        ?int $targetHeight = null,
    ): string {
        $contents = file_get_contents($upload->getRealPath());
        $source = $contents === false ? false : @imagecreatefromstring($contents);

        if ($source === false) {
            throw new RuntimeException('The uploaded file is not a supported image.');
        }

        $source = $this->orientJpeg($source, $upload);
        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);

        if ($targetWidth && $targetHeight) {
            $output = $this->cropAndResize($source, $sourceWidth, $sourceHeight, $targetWidth, $targetHeight);
        } else {
            $output = $this->resizeWithinLimit($source, $sourceWidth, $sourceHeight);
        }

        $relativePath = trim($directory, '/').'/'.Str::uuid().'.webp';
        $absolutePath = Storage::disk('public')->path($relativePath);
        $outputDirectory = dirname($absolutePath);

        if (! is_dir($outputDirectory) && ! mkdir($outputDirectory, 0755, true) && ! is_dir($outputDirectory)) {
            imagedestroy($source);
            imagedestroy($output);
            throw new RuntimeException('Unable to create the image storage directory.');
        }

        $stored = imagewebp($output, $absolutePath, self::WEBP_QUALITY);

        imagedestroy($source);
        imagedestroy($output);

        if (! $stored) {
            throw new RuntimeException('Unable to store the optimized image.');
        }

        return $relativePath;
    }

    public function replace(
        UploadedFile $upload,
        string $directory,
        ?string $oldPath = null,
        ?int $targetWidth = null,
        ?int $targetHeight = null,
    ): string {
        $newPath = $this->store($upload, $directory, $targetWidth, $targetHeight);

        if ($oldPath && $oldPath !== $newPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return $newPath;
    }

    public function delete(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }

    private function resizeWithinLimit(\GdImage $source, int $width, int $height): \GdImage
    {
        $scale = min(1, self::MAX_DIMENSION / max($width, $height));
        $targetWidth = max(1, (int) round($width * $scale));
        $targetHeight = max(1, (int) round($height * $scale));

        return $this->resample($source, 0, 0, $width, $height, $targetWidth, $targetHeight);
    }

    private function cropAndResize(
        \GdImage $source,
        int $width,
        int $height,
        int $targetWidth,
        int $targetHeight,
    ): \GdImage {
        $targetRatio = $targetWidth / $targetHeight;
        $sourceRatio = $width / $height;

        if ($sourceRatio > $targetRatio) {
            $cropHeight = $height;
            $cropWidth = (int) round($height * $targetRatio);
            $sourceX = (int) round(($width - $cropWidth) / 2);
            $sourceY = 0;
        } else {
            $cropWidth = $width;
            $cropHeight = (int) round($width / $targetRatio);
            $sourceX = 0;
            $sourceY = (int) round(($height - $cropHeight) / 2);
        }

        return $this->resample(
            $source,
            $sourceX,
            $sourceY,
            $cropWidth,
            $cropHeight,
            $targetWidth,
            $targetHeight,
        );
    }

    private function resample(
        \GdImage $source,
        int $sourceX,
        int $sourceY,
        int $sourceWidth,
        int $sourceHeight,
        int $targetWidth,
        int $targetHeight,
    ): \GdImage {
        $output = imagecreatetruecolor($targetWidth, $targetHeight);
        imagealphablending($output, false);
        imagesavealpha($output, true);
        $transparent = imagecolorallocatealpha($output, 0, 0, 0, 127);
        imagefill($output, 0, 0, $transparent);

        imagecopyresampled(
            $output,
            $source,
            0,
            0,
            $sourceX,
            $sourceY,
            $targetWidth,
            $targetHeight,
            $sourceWidth,
            $sourceHeight,
        );

        return $output;
    }

    private function orientJpeg(\GdImage $source, UploadedFile $upload): \GdImage
    {
        if ($upload->getMimeType() !== 'image/jpeg' || ! function_exists('exif_read_data')) {
            return $source;
        }

        $exif = @exif_read_data($upload->getRealPath());
        $orientation = (int) ($exif['Orientation'] ?? 1);
        $rotated = match ($orientation) {
            3 => imagerotate($source, 180, 0),
            6 => imagerotate($source, -90, 0),
            8 => imagerotate($source, 90, 0),
            default => false,
        };

        if ($rotated === false) {
            return $source;
        }

        imagedestroy($source);

        return $rotated;
    }
}
