<?php

namespace Database\Seeders;

use App\Models\TireSize;
use Illuminate\Database\Seeder;

class TireSizeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $sizes = [
      ['width' => 165, 'aspect_ratio' => 65, 'rim_diameter' => 13],
      ['width' => 165, 'aspect_ratio' => 70, 'rim_diameter' => 13],
      ['width' => 165, 'aspect_ratio' => 70, 'rim_diameter' => 14],
      ['width' => 175, 'aspect_ratio' => 70, 'rim_diameter' => 14],
      ['width' => 185, 'aspect_ratio' => 60, 'rim_diameter' => 15],
      ['width' => 185, 'aspect_ratio' => 65, 'rim_diameter' => 15],
      ['width' => 195, 'aspect_ratio' => 65, 'rim_diameter' => 15],
      ['width' => 195, 'aspect_ratio' => 60, 'rim_diameter' => 15],
      ['width' => 205, 'aspect_ratio' => 60, 'rim_diameter' => 16],
      ['width' => 205, 'aspect_ratio' => 65, 'rim_diameter' => 16],
      ['width' => 215, 'aspect_ratio' => 65, 'rim_diameter' => 16],
    ];

    foreach ($sizes as $size) {
      $slug = "{$size['width']}-{$size['aspect_ratio']}-r{$size['rim_diameter']}";

      TireSize::create([
        'width' => $size['width'],
        'aspect_ratio' => $size['aspect_ratio'],
        'rim_diameter' => $size['rim_diameter'],
        'slug' => $slug,
        'is_active' => true,
      ]);
    }
  }
}
