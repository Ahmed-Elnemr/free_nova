<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

final class UploadedFileFactory
{
    public static function from(mixed $value): ?UploadedFile
    {
        if ($value instanceof UploadedFile) {
            return $value;
        }

        if (is_array($value)) {
            $value = Arr::first($value);
        }

        if (! is_string($value) || $value === '') {
            return null;
        }

        $absolute = Storage::disk('public')->path($value);

        if (! is_file($absolute)) {
            return null;
        }

        return new UploadedFile(
            $absolute,
            basename($absolute),
            mime_content_type($absolute) ?: null,
            null,
            true,
        );
    }
}
