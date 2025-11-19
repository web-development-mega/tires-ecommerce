<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tire_sizes', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('width');        // e.g. 205
            $table->unsignedSmallInteger('aspect_ratio'); // e.g. 55
            $table->decimal('rim_diameter', 4, 1);        // e.g. 16.0
            $table->string('slug', 50)->unique();         // e.g. '205-55-R16'
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(
                ['width', 'aspect_ratio', 'rim_diameter'],
                'tire_sizes_unique_size'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tire_sizes');
    }
};
