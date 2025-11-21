<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TireIndexRequest;
use App\Http\Resources\TireResource;
use App\Models\Tire;
use Illuminate\Http\JsonResponse;

class TireController extends Controller
{
    /**
     * GET /api/tires
     *
     * Public tires listing with basic filters.
     */
    public function index(TireIndexRequest $request): JsonResponse
    {
        $filters = $request->filters();
        $perPage = $request->perPage();

        $query = Tire::query()
            ->with(['brand', 'size']);

        // Brand filters
        if (! empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        if (! empty($filters['brand_slug'])) {
            $query->whereHas('brand', function ($q) use ($filters) {
                $q->where('slug', $filters['brand_slug']);
            });
        }

        // Size filters (ANCHO / PERFIL / RIN)
        if ($filters['width'] || $filters['profile'] || $filters['rim']) {
            $query->whereHas('size', function ($q) use ($filters) {
                if (! empty($filters['width'])) {
                    $q->where('section_width', $filters['width']);
                }
                if (! empty($filters['profile'])) {
                    $q->where('aspect_ratio', $filters['profile']);
                }
                if (! empty($filters['rim'])) {
                    $q->where('rim_diameter', $filters['rim']);
                }
            });
        }

        // Boolean flags (runflat / all_terrain)
        if ($filters['runflat'] !== null) {
            $query->where('is_runflat', $filters['runflat']);
        }

        if ($filters['all_terrain'] !== null) {
            $query->where('is_all_terrain', $filters['all_terrain']);
        }

        // Podríamos añadir más filtros (precio, UTQG, etc.) más adelante.

        $paginator = $query->paginate($perPage);

        return TireResource::collection($paginator)->response();
    }

    /**
     * GET /api/tires/{tire}
     *
     * Show tire detail.
     */
    public function show(Tire $tire): JsonResponse
    {
        $tire->load(['brand', 'size']);

        return TireResource::make($tire)->response();
    }
}
