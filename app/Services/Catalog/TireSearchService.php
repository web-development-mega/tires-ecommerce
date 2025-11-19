<?php

namespace App\Services\Catalog;

use App\Models\Tire;
use App\Models\TireSize;
use App\Models\Vehicle;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class TireSearchService
{
    public function __construct(
        private readonly int $defaultPerPage = 20
    ) {
    }

    /**
     * Search tires compatible with a given vehicle.
     */
    public function searchByVehicle(
        Vehicle $vehicle,
        array $filters = [],
        ?int $perPage = null
    ): LengthAwarePaginator {
        $query = Tire::query()
            ->active()
            ->forVehicle($vehicle);

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage ?? $this->defaultPerPage);
    }

    /**
     * Search tires by size (width / aspect_ratio / rim).
     */
    public function searchBySize(
        int $width,
        int $aspectRatio,
        float|int $rimDiameter,
        array $filters = [],
        ?int $perPage = null
    ): LengthAwarePaginator {
        $query = Tire::query()
            ->active()
            ->forSize($width, $aspectRatio, $rimDiameter);

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage ?? $this->defaultPerPage);
    }

    /**
     * Search tires by TireSize entity (useful when size is already resolved).
     */
    public function searchByTireSize(
        TireSize $size,
        array $filters = [],
        ?int $perPage = null
    ): LengthAwarePaginator {
        $query = Tire::query()
            ->active()
            ->where('tire_size_id', $size->id);

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage ?? $this->defaultPerPage);
    }

    /**
     * Apply additional filters (brand, price range, flags...).
     */
    protected function applyFilters(Builder $query, array $filters): void
    {
        if (isset($filters['brand_id']) && $filters['brand_id']) {
            $query->forBrand((int) $filters['brand_id']);
        }

        if (isset($filters['min_price']) || isset($filters['max_price'])) {
            $query->filterPriceBetween(
                isset($filters['min_price']) ? (float) $filters['min_price'] : null,
                isset($filters['max_price']) ? (float) $filters['max_price'] : null
            );
        }

        if (! empty($filters['is_runflat'])) {
            $query->where('is_runflat', true);
        }

        if (! empty($filters['is_all_terrain'])) {
            $query->where('is_all_terrain', true);
        }

        if (! empty($filters['usage'])) {
            $query->where('usage', $filters['usage']); // value of TireUsage enum
        }

        if (isset($filters['min_load_index'])) {
            $query->where('load_index', '>=', (int) $filters['min_load_index']);
        }

        if (isset($filters['min_speed_rating'])) {
            $query->where('speed_rating', '>=', $filters['min_speed_rating']);
        }

        // Aquí se pueden añadir filtros por UTQG, etiqueta, ruido, etc.
    }
}
