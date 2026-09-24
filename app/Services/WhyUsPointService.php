<?php

namespace App\Services;

use App\Repositories\Contracts\WhyUsPointRepositoryContract;
use App\Services\Concerns\PreparesSluggedAttributes;
use Illuminate\Database\Eloquent\Model;

class WhyUsPointService extends BaseModelService
{
    use PreparesSluggedAttributes;

    public function __construct(WhyUsPointRepositoryContract $repository)
    {
        parent::__construct($repository);
    }

    protected function slugSourceKey(): string
    {
        return 'body';
    }

    public function create(array $data): Model
    {
        return parent::create($this->withSlug($data));
    }

    public function update(int $id, array $data): ?Model
    {
        return parent::update($id, $this->withSlug($data, true));
    }
}
