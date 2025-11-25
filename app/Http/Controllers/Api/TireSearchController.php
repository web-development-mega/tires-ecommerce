<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchTiresBySizeRequest;
use App\Http\Requests\SearchTiresByVehicleRequest;
use App\Http\Resources\TireResource;
use App\Models\Vehicle;
use App\Services\Catalog\TireSearchService;
use Illuminate\Http\JsonResponse;

class TireSearchController extends Controller
{
    public function __construct(
        private readonly TireSearchService $tireSearchService
    ) {}

    /**
     * Search tires compatible with a given vehicle.
     *
     * GET /api/tires/search/by-vehicle
     */
    public function byVehicle(SearchTiresByVehicleRequest $request): JsonResponse
    {
        $vehicle = Vehicle::query()
            ->with(['brand', 'line', 'version'])
            ->findOrFail($request->input('vehicle_id'));

        $perPage = $request->integer('per_page', 20);
        $filters = $request->filters();
        $paginator = $this->tireSearchService->searchByVehicle($vehicle, $filters, $perPage);

        $resource = TireResource::collection($paginator);

        return $resource
            ->additional([
                'meta' => [
                    'filters' => $filters,
                    'vehicle' => [
                        'id' => $vehicle->id,
                        'brand' => $vehicle->brand?->name,
                        'line' => $vehicle->line?->name,
                        'version' => $vehicle->version?->name,
                        'year' => $vehicle->year,
                    ],
                ],
            ])
            ->response();
    }

    /**
     * Search tires by size (width / aspect_ratio / rim_diameter).
     *
     * GET /api/tires/search/by-size
     */
    public function bySize(SearchTiresBySizeRequest $request): JsonResponse
    {
        $width = $request->width();
        $aspectRatio = $request->aspectRatio();
        $rimDiameter = $request->rimDiameter();
        $perPage = $request->integer('per_page', 20);
        $filters = $request->filters();

        $paginator = $this->tireSearchService->searchBySize(
            $width,
            $aspectRatio,
            $rimDiameter,
            $filters,
            $perPage
        );

        $resource = TireResource::collection($paginator);

        return $resource
            ->additional([
                'meta' => [
                    'filters' => $filters,
                    'size' => [
                        'width' => $width,
                        'aspect_ratio' => $aspectRatio,
                        'rim_diameter' => $rimDiameter,
                    ],
                ],
            ])
            ->response();
    }
}
