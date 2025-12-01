@extends('layouts.app')

@section('title', ($tire->brand->name ?? '').' '.$tire->name.' | Ficha de producto')

@section('meta_description', 'Ficha técnica y precio de la llanta '.(($tire->brand->name ?? '').' '.$tire->name).' con instalación incluida.')

@section('content')
    @php
        /** @var \App\Models\Tire $tire */
        $size = $tire->tireSize ?? $tire->size ?? null;
        $brand = $tire->brand ?? null;
        $effectivePrice = $tire->effective_price ?? $tire->sale_price ?? $tire->base_price;
        $cartToken = request()->cookie('cart_token');
    @endphp

    <section class="bg-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
            <div class="mb-4 text-xs text-slate-400 flex flex-wrap items-center gap-2">
                <a href="{{ route('tires.index') }}" class="hover:text-slate-200">Catálogo</a>
                <span>/</span>
                @if($brand)
                    <span>{{ $brand->name }}</span>
                @endif
                <span>/</span>
                <span class="text-slate-200">{{ $tire->name }}</span>
            </div>

            <div class="grid gap-8 lg:grid-cols-[minmax(0,1.2fr),minmax(0,1fr)] items-start">
                {{-- Imagen + branding --}}
                <div class="relative rounded-[2rem] bg-slate-900/80 border border-slate-800/80 p-4 sm:p-6 shadow-card overflow-hidden">
                    <div class="absolute -left-20 -top-20 h-64 w-64 rounded-full bg-brand/20 blur-3xl"></div>
                    <div class="absolute -right-32 bottom-0 h-72 w-72 rounded-full bg-accent/10 blur-3xl"></div>

                    <div class="relative grid gap-4 sm:gap-6 sm:grid-cols-[minmax(0,1.2fr),minmax(0,1fr)] items-center">
                        <div class="relative aspect-[4/3] bg-slate-950/80 rounded-2xl flex items-center justify-center overflow-hidden">
                            @if($tire->image_url)
                                <img src="{{ $tire->image_url }}"
                                     alt="{{ $tire->name }}"
                                     class="h-full w-full object-contain">
                            @else
                                <span class="text-xs text-slate-500">
                                    Imagen de referencia no disponible
                                </span>
                            @endif

                            @if($size)
                                <div class="absolute left-4 top-4 inline-flex items-center rounded-full bg-slate-950/80 border border-slate-800/80 px-3 py-1 text-[11px] text-slate-200">
                                    {{ $size->width }}/{{ $size->aspect_ratio }} R{{ $size->rim_diameter }}
                                </div>
                            @endif
                        </div>

                        <div class="space-y-3">
                            @if($brand)
                                <p class="text-[11px] uppercase tracking-[0.18em] text-slate-500">
                                    {{ $brand->name }}
                                </p>
                            @endif

                            <h1 class="text-xl sm:text-2xl font-semibold text-white">
                                {{ $tire->name }}
                            </h1>

                            @if($tire->pattern)
                                <p class="text-sm text-slate-400">
                                    Diseño: {{ $tire->pattern }}
                                </p>
                            @endif

                            <div class="space-y-1.5 text-sm text-slate-300">
                                @if($tire->usage)
                                    <p>Uso recomendado: <span class="font-medium text-slate-100">
                                        {{ str_replace('_', ' ', strtolower($tire->usage->name ?? $tire->usage)) }}
                                    </span></p>
                                @endif

                                @if($tire->load_index || $tire->speed_rating)
                                    <p>
                                        Índice de carga y velocidad:
                                        <span class="font-medium text-slate-100">
                                            {{ $tire->load_index }}{{ $tire->speed_rating ? ' / '.$tire->speed_rating : '' }}
                                        </span>
                                    </p>
                                @endif
                            </div>

                            <div class="pt-2 space-y-3">
                                <div class="space-y-0.5">
                                    @if($tire->sale_price && $tire->sale_price < $tire->base_price)
                                        <p class="text-sm text-slate-500 line-through">
                                            $ {{ number_format($tire->base_price, 0, ',', '.') }}
                                        </p>
                                        <p class="text-2xl font-semibold text-accent">
                                            $ {{ number_format($tire->sale_price, 0, ',', '.') }}
                                        </p>
                                    @else
                                        <p class="text-2xl font-semibold text-accent">
                                            $ {{ number_format($effectivePrice, 0, ',', '.') }}
                                        </p>
                                    @endif
                                    <p class="text-xs text-slate-400">
                                        Precio por llanta. Instalación básica y balanceo incluidos en servitecas aliadas.
                                    </p>
                                </div>

                                <form method="POST" action="{{ route('cart.store') }}" class="space-y-3">
                                    @csrf
                                    <input type="hidden" name="cart_token" value="{{ $cartToken }}">
                                    <input type="hidden" name="tire_id" value="{{ $tire->id }}">

                                    <div class="flex items-center gap-2">
                                        <label for="quantity" class="text-xs text-slate-300">Cantidad:</label>
                                        <input
                                            type="number"
                                            name="quantity"
                                            id="quantity"
                                            min="1"
                                            max="8"
                                            value="2"
                                            class="w-20 rounded-xl border-slate-700/80 bg-slate-950/60 text-sm text-slate-100 focus:border-brand focus:ring-brand">
                                    </div>

                                    <button
                                        type="submit"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand text-white text-sm font-semibold px-4 py-2.5 hover:bg-brand/90 transition">
                                        <span>Agregar al carrito</span>
                                        <span aria-hidden="true">🛒</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Info técnica / beneficios --}}
                <aside class="space-y-4">
                    <div class="rounded-2xl bg-slate-900/80 border border-slate-800/80 p-4 sm:p-5 text-sm text-slate-300">
                        <h2 class="text-sm font-semibold text-slate-100 mb-3">Resumen técnico</h2>
                        <dl class="space-y-1.5 text-xs sm:text-sm">
                            @if($size)
                                <div class="flex justify-between gap-4">
                                    <dt class="text-slate-400">Medida</dt>
                                    <dd class="text-slate-100">
                                        {{ $size->width }}/{{ $size->aspect_ratio }} R{{ $size->rim_diameter }}
                                    </dd>
                                </div>
                            @endif
                            @if($tire->utqg_treadwear)
                                <div class="flex justify-between gap-4">
                                    <dt class="text-slate-400">Treadwear</dt>
                                    <dd class="text-slate-100">{{ $tire->utqg_treadwear }}</dd>
                                </div>
                            @endif
                            @if($tire->utqg_traction)
                                <div class="flex justify-between gap-4">
                                    <dt class="text-slate-400">Tracción</dt>
                                    <dd class="text-slate-100">{{ $tire->utqg_traction }}</dd>
                                </div>
                            @endif
                            @if($tire->utqg_temperature)
                                <div class="flex justify-between gap-4">
                                    <dt class="text-slate-400">Temperatura</dt>
                                    <dd class="text-slate-100">{{ $tire->utqg_temperature }}</dd>
                                </div>
                            @endif
                            @if($tire->country_of_origin)
                                <div class="flex justify-between gap-4">
                                    <dt class="text-slate-400">País de origen</dt>
                                    <dd class="text-slate-100">{{ $tire->country_of_origin }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <div class="rounded-2xl bg-slate-900/80 border border-slate-800/80 p-4 sm:p-5 text-xs sm:text-sm text-slate-300 space-y-2">
                        <h2 class="text-sm font-semibold text-slate-100 mb-1">Instalación y garantías</h2>
                        <p>
                            La instalación se realiza en servitecas aliadas con equipos de montaje y balanceo profesional.
                        </p>
                        <p>
                            La garantía aplica directamente con el fabricante y cubre defectos de fabricación según política de cada marca.
                        </p>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
