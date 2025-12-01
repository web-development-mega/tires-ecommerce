<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Tire;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
  /**
   * Home / landing con buscador y productos destacados.
   */
  public function index(): View
  {
    // Llantas destacadas (por ahora: últimas activas)
    $featuredTires = Tire::query()
      ->active()
      ->with(['brand', 'tireSize'])
      ->orderByDesc('id')
      ->limit(8)
      ->get();

    // Marcas para el filtro por marca
    $brands = Brand::query()
      ->where('is_active', true)
      ->orderBy('name')
      ->get(['id', 'name', 'slug', 'image_url']);

    return view('home.index', [
      'featuredTires' => $featuredTires,
      'brands'        => $brands,
    ]);
  }
}
