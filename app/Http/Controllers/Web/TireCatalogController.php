<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Tire;
use App\Services\Catalog\TireSearchService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TireCatalogController extends Controller
{
  public function __construct(
    private readonly TireSearchService $tireSearchService
  ) {}

  /**
   * Listado de llantas con filtros (MVP: pronto lo rellenamos).
   */
  public function index(Request $request): View
  {
    // Por ahora, listado simple paginado sin aplicar filtros.
    $tires = Tire::query()
      ->active()
      ->with(['brand', 'tireSize'])
      ->orderBy('name')
      ->paginate(12)
      ->withQueryString();

    // Obtener todas las marcas para el filtro
    $brands = Brand::query()
      ->orderBy('name')
      ->get();

    return view('tires.index', [
      'tires' => $tires,
      'brands' => $brands,
    ]);
  }
  /**
   * Ficha de producto.
   */
  public function show(string $slug): View
  {
    $tire = Tire::query()
      ->active()
      ->where('slug', $slug)
      ->with(['brand', 'tireSize'])
      ->firstOrFail();

    return view('tires.show', [
      'tire' => $tire,
    ]);
  }
}
