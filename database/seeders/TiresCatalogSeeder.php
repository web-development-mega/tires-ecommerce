<?php

namespace Database\Seeders;

use App\Enums\FitmentPosition;
use App\Enums\TireUsage;
use App\Enums\VehicleType;
use App\Models\Brand;
use App\Models\Tire;
use App\Models\TireSize;
use App\Models\Vehicle;
use App\Models\VehicleBrand;
use App\Models\VehicleLine;
use App\Models\VehicleTireFitment;
use App\Models\VehicleVersion;
use Illuminate\Database\Seeder;

class TiresCatalogSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Tire Brands
        $michelin = Brand::create([
            'name' => 'Michelin',
            'slug' => 'michelin',
            'website_url' => 'https://www.michelin.com',
            'country' => 'Francia',
            'is_active' => true,
        ]);

        $goodyear = Brand::create([
            'name' => 'Goodyear',
            'slug' => 'goodyear',
            'website_url' => 'https://www.goodyear.com',
            'country' => 'Estados Unidos',
            'is_active' => true,
        ]);

        $bridgestone = Brand::create([
            'name' => 'Bridgestone',
            'slug' => 'bridgestone',
            'website_url' => 'https://www.bridgestone.com',
            'country' => 'Japón',
            'is_active' => true,
        ]);

        // 2. Create Tire Sizes
        $size205_55_16 = TireSize::create([
            'width' => 205,
            'aspect_ratio' => 55,
            'rim_diameter' => 16.0,
            'slug' => '205-55-r16',
            'is_active' => true,
        ]);

        $size215_60_17 = TireSize::create([
            'width' => 215,
            'aspect_ratio' => 60,
            'rim_diameter' => 17.0,
            'slug' => '215-60-r17',
            'is_active' => true,
        ]);

        $size225_65_17 = TireSize::create([
            'width' => 225,
            'aspect_ratio' => 65,
            'rim_diameter' => 17.0,
            'slug' => '225-65-r17',
            'is_active' => true,
        ]);

        // 3. Create Tires
        Tire::create([
            'brand_id' => $michelin->id,
            'tire_size_id' => $size205_55_16->id,
            'sku' => 'MICH-PRIM-205-55-16',
            'name' => 'Michelin Primacy 4 205/55R16',
            'slug' => 'michelin-primacy-4-205-55-r16',
            'pattern' => 'Primacy 4',
            'load_index' => 91,
            'speed_rating' => 'V',
            'usage' => TireUsage::PASSENGER,
            'is_summer' => true,
            'base_price' => 450000,
            'currency' => 'COP',
            'is_active' => true,
        ]);

        Tire::create([
            'brand_id' => $goodyear->id,
            'tire_size_id' => $size205_55_16->id,
            'sku' => 'GOOD-ASSU-205-55-16',
            'name' => 'Goodyear Assurance 205/55R16',
            'slug' => 'goodyear-assurance-205-55-r16',
            'pattern' => 'Assurance',
            'load_index' => 91,
            'speed_rating' => 'H',
            'usage' => TireUsage::PASSENGER,
            'is_summer' => true,
            'base_price' => 380000,
            'currency' => 'COP',
            'is_active' => true,
        ]);

        Tire::create([
            'brand_id' => $bridgestone->id,
            'tire_size_id' => $size215_60_17->id,
            'sku' => 'BRID-TURZ-215-60-17',
            'name' => 'Bridgestone Turanza T005 215/60R17',
            'slug' => 'bridgestone-turanza-t005-215-60-r17',
            'pattern' => 'Turanza T005',
            'load_index' => 96,
            'speed_rating' => 'H',
            'usage' => TireUsage::PASSENGER,
            'is_summer' => true,
            'base_price' => 520000,
            'currency' => 'COP',
            'is_active' => true,
        ]);

        Tire::create([
            'brand_id' => $michelin->id,
            'tire_size_id' => $size225_65_17->id,
            'sku' => 'MICH-LAT-225-65-17',
            'name' => 'Michelin Latitude Tour HP 225/65R17',
            'slug' => 'michelin-latitude-tour-hp-225-65-r17',
            'pattern' => 'Latitude Tour HP',
            'load_index' => 102,
            'speed_rating' => 'H',
            'usage' => TireUsage::SUV,
            'is_highway' => true,
            'base_price' => 680000,
            'currency' => 'COP',
            'is_active' => true,
        ]);

        // 4. Create Vehicle Brands
        $chevrolet = VehicleBrand::create([
            'name' => 'Chevrolet',
            'slug' => 'chevrolet',
            'country' => 'Estados Unidos',
            'is_active' => true,
        ]);

        $renault = VehicleBrand::create([
            'name' => 'Renault',
            'slug' => 'renault',
            'country' => 'Francia',
            'is_active' => true,
        ]);

        // 5. Create Vehicle Lines
        $spark = VehicleLine::create([
            'vehicle_brand_id' => $chevrolet->id,
            'name' => 'Spark',
            'slug' => 'spark',
            'is_active' => true,
        ]);

        $duster = VehicleLine::create([
            'vehicle_brand_id' => $renault->id,
            'name' => 'Duster',
            'slug' => 'duster',
            'is_active' => true,
        ]);

        // 6. Create Vehicle Versions
        $sparkGT = VehicleVersion::create([
            'vehicle_line_id' => $spark->id,
            'name' => 'GT 1.4L',
            'slug' => 'gt-1-4l',
            'is_active' => true,
        ]);

        $dusterZen = VehicleVersion::create([
            'vehicle_line_id' => $duster->id,
            'name' => 'Zen 1.6L 4x2',
            'slug' => 'zen-1-6l-4x2',
            'is_active' => true,
        ]);

        // 7. Create Vehicles
        $sparkGT2020 = Vehicle::create([
            'vehicle_brand_id' => $chevrolet->id,
            'vehicle_line_id' => $spark->id,
            'vehicle_version_id' => $sparkGT->id,
            'year' => 2020,
            'type' => VehicleType::CAR,
            'engine' => '1.4L',
            'fuel_type' => 'Gasolina',
            'body_type' => 'Hatchback',
            'is_active' => true,
        ]);

        $duster2021 = Vehicle::create([
            'vehicle_brand_id' => $renault->id,
            'vehicle_line_id' => $duster->id,
            'vehicle_version_id' => $dusterZen->id,
            'year' => 2021,
            'type' => VehicleType::SUV,
            'engine' => '1.6L',
            'fuel_type' => 'Gasolina',
            'body_type' => 'SUV',
            'is_active' => true,
        ]);

        // 8. Create Vehicle Tire Fitments
        VehicleTireFitment::create([
            'vehicle_id' => $sparkGT2020->id,
            'tire_size_id' => $size205_55_16->id,
            'position' => FitmentPosition::BOTH,
            'is_oem' => true,
            'is_primary' => true,
            'min_load_index' => 91,
            'min_speed_rating' => 'H',
        ]);

        VehicleTireFitment::create([
            'vehicle_id' => $duster2021->id,
            'tire_size_id' => $size215_60_17->id,
            'position' => FitmentPosition::BOTH,
            'is_oem' => true,
            'is_primary' => true,
            'min_load_index' => 96,
            'min_speed_rating' => 'H',
        ]);

        VehicleTireFitment::create([
            'vehicle_id' => $duster2021->id,
            'tire_size_id' => $size225_65_17->id,
            'position' => FitmentPosition::BOTH,
            'is_oem' => false,
            'is_primary' => false,
            'min_load_index' => 102,
            'min_speed_rating' => 'H',
            'notes' => 'Medida alternativa compatible',
        ]);

        $this->command->info('✅ Catálogo de llantas y vehículos creado exitosamente');
        $this->command->info('📊 Vehículos disponibles:');
        $this->command->info('   - ID 1: Chevrolet Spark GT 1.4L 2020');
        $this->command->info('   - ID 2: Renault Duster Zen 1.6L 4x2 2021');
    }
}
