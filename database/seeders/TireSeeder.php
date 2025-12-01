<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Tire;
use App\Models\TireSize;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TireSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $tires = [
      ['size' => '165/65R13', 'brand' => 'Royal Black'],
      ['size' => '165/65R13', 'brand' => 'Delmax'],
      ['size' => '165/70R13', 'brand' => 'Blazer Compazal'],
      ['size' => '165/70R13', 'brand' => 'Fronway'],
      ['size' => '165/70R14', 'brand' => 'Royal Black'],
      ['size' => '165/70R14', 'brand' => 'Delmax'],
      ['size' => '175/70R14', 'brand' => 'Royal Black'],
      ['size' => '175/70R14', 'brand' => 'Delmax'],
      ['size' => '185/60R15', 'brand' => 'Royal Black'],
      ['size' => '185/60R15', 'brand' => 'Delmax'],
      ['size' => '185/65R15', 'brand' => 'Royal Black'],
      ['size' => '185/65R15', 'brand' => 'Delmax'],
      ['size' => '195/65R15', 'brand' => 'Royal Black'],
      ['size' => '195/65R15', 'brand' => 'Delmax'],
      ['size' => '195/60R15', 'brand' => 'Royal Black'],
      ['size' => '195/60R15', 'brand' => 'Delmax'],
      ['size' => '205/60R16', 'brand' => 'Royal Black'],
      ['size' => '205/60R16', 'brand' => 'Delmax'],
      ['size' => '205/65R16', 'brand' => 'Royal Black'],
      ['size' => '205/65R16', 'brand' => 'Delmax'],
      ['size' => '215/65R16', 'brand' => 'Royal Black'],
      ['size' => '215/65R16', 'brand' => 'Delmax'],
      ['size' => '215/65R16', 'brand' => 'Power Track'],
      ['size' => '215/65R16', 'brand' => 'Rovelo'],
      ['size' => '215/65R16', 'brand' => 'Power Wildrange'],
    ];

    foreach ($tires as $tireData) {
      // Parse size (e.g., "165/65R13" -> width: 165, aspect: 65, rim: 13)
      preg_match('/(\d+)\/(\d+)R(\d+)/', $tireData['size'], $matches);
      $width = (int) $matches[1];
      $aspectRatio = (int) $matches[2];
      $rimDiameter = (int) $matches[3];

      // Find brand
      $brand = Brand::where('name', $tireData['brand'])->first();
      if (! $brand) {
        continue;
      }

      // Find tire size
      $tireSize = TireSize::where('width', $width)
        ->where('aspect_ratio', $aspectRatio)
        ->where('rim_diameter', $rimDiameter)
        ->first();

      if (! $tireSize) {
        continue;
      }

      // Create tire
      $name = "{$brand->name} {$tireData['size']}";
      $sku = strtoupper(Str::slug($name, ''));

      Tire::create([
        'brand_id' => $brand->id,
        'tire_size_id' => $tireSize->id,
        'sku' => $sku,
        'name' => $name,
        'slug' => Str::slug($name),
        'base_price' => rand(150000, 500000), // Precios aleatorios entre 150k y 500k COP
        'currency' => 'COP',
        'is_active' => true,
      ]);
    }
  }
}
