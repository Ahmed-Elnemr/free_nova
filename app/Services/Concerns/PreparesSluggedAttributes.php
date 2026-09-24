<?php

namespace App\Services\Concerns;

use Illuminate\Support\Str;

trait PreparesSluggedAttributes
{
    protected function slugSourceKey(): string
    {
        return 'name';
    }

    protected function withSlug(array $data, bool $isUpdate = false): array
    {
        $slug = trim((string) ($data['slug'] ?? ''));

        if ($slug === '' && ! $isUpdate) {
            $source = $data[$this->slugSourceKey()]['en']
                ?? $data[$this->slugSourceKey()]['ar']
                ?? Str::random(8);
            $slug = Str::slug($source) ?: Str::lower(Str::random(8));
        }

        if ($slug !== '') {
            $data['slug'] = $slug;
        } else {
            unset($data['slug']);
        }

        if (array_key_exists('sort_order', $data)) {
            $data['sort_order'] = (int) $data['sort_order'];
        } elseif (! $isUpdate) {
            $data['sort_order'] = 0;
        }

        if (array_key_exists('is_active', $data)) {
            $data['is_active'] = (bool) $data['is_active'];
        } elseif (! $isUpdate) {
            $data['is_active'] = true;
        }

        return $data;
    }
}
