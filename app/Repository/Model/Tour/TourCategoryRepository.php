<?php

namespace App\Repository\Model\Tour;

use App\Models\Tour\TourCategory;
use App\Repository\Abstracts\AttributeRepository;
use Illuminate\Support\Collection;

/**
 * @property-read TourCategory $model
 */
class TourCategoryRepository extends AttributeRepository
{
    public static function getName(): string
    {
        return 'Tour Categories';
    }

    /**
     * @param bool $trashed
     * @return TourCategory[]|Collection
     */ 
    public static function getAll(bool $trashed = false): array|Collection
    {
        if ($trashed) {
            return TourCategory::withTrashed()->withCount('tours')->get();
        } else {
            return TourCategory::withCount('tours')->get();
        }
    }

    public static function getCreateUrl(): string|null
    {
        return route('tour-categories.create');
    }

    public function getEditUrl(): string|null
    {
        return route('tour-categories.update', ['tourCategory' => $this->model,]);
    }

    public function getDeleteUrl(): string|null
    {
        if ($this->canDelete()) {
            return route('tour-categories.delete', ['tourCategory' => $this->model,]);
        }
        return null;
    }

    public function getRelatedCount(): int
    {
        return $this->model->tours()->count();
    }
}
