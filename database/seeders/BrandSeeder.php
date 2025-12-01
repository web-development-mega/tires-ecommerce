<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $brands = [
      ['name' => 'Royal Black', 'country' => 'China'],
      ['name' => 'Delmax', 'country' => 'China'],
      ['name' => 'Blazer Compazal', 'country' => 'China'],
      ['name' => 'Fronway', 'country' => 'China'],
      ['name' => 'Power Track', 'country' => 'China'],
      ['name' => 'Rovelo', 'country' => 'China'],
      ['name' => 'Power Wildrange', 'country' => 'China'],
    ];

    foreach ($brands as $brand) {
      Brand::create([
        'name' => $brand['name'],
        'slug' => Str::slug($brand['name']),
        'country' => $brand['country'],
        'is_active' => true,
      ]);
    }
  }
}
