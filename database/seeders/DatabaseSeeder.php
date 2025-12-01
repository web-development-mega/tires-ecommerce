<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  use WithoutModelEvents;

  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // Seed brands first
    $this->call([
      BrandSeeder::class,
      TireSizeSeeder::class,
      TireSeeder::class,
    ]);

    // Create admin user if not exists
    if (! User::where('email', 'admin@megallantas.com')->exists()) {
      User::factory()->create([
        'name' => 'Admin',
        'email' => 'admin@megallantas.com',
        'password' => bcrypt('admin123'),
      ]);
    }
  }
}
