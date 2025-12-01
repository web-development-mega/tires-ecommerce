<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'slug',
    'website_url',
    'country',
    'image_url',
    'is_active',
  ];

  protected $casts = [
    'is_active' => 'boolean',
  ];

  public function tires(): HasMany
  {
    return $this->hasMany(Tire::class);
  }

  public function getImageUrlAttribute($value): ?string
  {
    if (!$value) {
      return null;
    }

    // Si ya es una URL completa (Cloudinary o local), devolverla tal cual
    if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
      return $value;
    }

    // Fallback para imágenes locales antiguas (no debería ocurrir después de la migración)
    return asset('storage/' . $value);
  }  // Alias para compatibilidad con vistas que usan logo_url
  public function getLogoUrlAttribute(): ?string
  {
    return $this->image_url;
  }
}
