<?php

namespace App\Services;

use App\Facades\MediaUpload;
use App\Services\Concerns\PreparesSluggedAttributes;
use App\Support\UploadedFileFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

abstract class MediaContentService extends BaseModelService
{
    use PreparesSluggedAttributes;

    protected string $mediaCollection = 'image';

    protected string $mediaField = 'image';

    protected bool $usesSlug = true;

    public function create(array $data): Model
    {
        $image = Arr::pull($data, $this->mediaField);

        if ($this->usesSlug) {
            $data = $this->withSlug($data);
        }

        $model = parent::create($data);
        $this->storeMedia($model, $image);

        return $model->load('media');
    }

    public function update(int $id, array $data): ?Model
    {
        $image = Arr::pull($data, $this->mediaField);

        if ($this->usesSlug) {
            $data = $this->withSlug($data, true);
        }

        parent::update($id, $data);

        $model = $this->repository->showOrFail($id);
        $this->storeMedia($model, $image);

        return $model->load('media');
    }

    public function attachRemote(Model $model, string $url): void
    {
        $response = Http::timeout(25)->get($url);

        if (! $response->successful() || $response->body() === '') {
            return;
        }

        $extension = pathinfo((string) parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'bin';
        $temporary = tempnam(sys_get_temp_dir(), 'nova');
        $named = $temporary.'.'.$extension;
        file_put_contents($named, $response->body());

        $file = new UploadedFile($named, basename($named), null, null, true);

        MediaUpload::file($file)
            ->collection($this->mediaCollection)
            ->uploadTo($model);

        @unlink($named);
        @unlink($temporary);
    }

    protected function storeMedia(Model $model, mixed $image): void
    {
        $file = UploadedFileFactory::from($image);

        if (! $file instanceof UploadedFile) {
            return;
        }

        $storedPath = is_array($image) ? Arr::first($image) : (is_string($image) ? $image : null);

        MediaUpload::file($file)
            ->collection($this->mediaCollection)
            ->uploadTo($model);

        if (is_string($storedPath)) {
            Storage::disk('public')->delete($storedPath);
        }
    }
}
