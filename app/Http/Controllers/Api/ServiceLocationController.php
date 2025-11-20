<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListServiceLocationsRequest;
use App\Http\Resources\ServiceLocationResource;
use App\Models\ServiceLocation;
use Illuminate\Http\JsonResponse;

class ServiceLocationController extends Controller
{
    /**
     * GET /api/service-locations
     * List service locations filtered by municipality / service type.
     */
    public function index(ListServiceLocationsRequest $request): JsonResponse
    {
        $filters = $request->filters();
        $perPage = $request->perPage();

        $query = ServiceLocation::query()
            ->with('serviceTypes');

        if ($filters['only_active']) {
            $query->active();
        }

        $query
            ->forMunicipality($filters['municipality'])
            ->withServiceTypeSlug($filters['service_slug']);

        $paginator = $query->paginate($perPage);

        return ServiceLocationResource::collection($paginator)->response();
    }

    /**
     * GET /api/service-locations/{serviceLocation}
     */
    public function show(ServiceLocation $serviceLocation): JsonResponse
    {
        $serviceLocation->load('serviceTypes');

        return ServiceLocationResource::make($serviceLocation)->response();
    }
}
