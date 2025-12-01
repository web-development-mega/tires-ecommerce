@extends('layouts.app')

@section('title', 'Catálogo de llantas')
@section('meta_description', 'Explora el catálogo de llantas por medida, marca y tipo de uso. Compara precios y agenda instalación.')

@section('content')
    @php
        /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\App\Models\Tire[] $tires */
        $width = request('width');
        $profile = request('profile');
        $rim = request('rim');
        $brandSlug = request('brand_slug');
        $cartToken = request()->cookie('cart_token');
    @endphp

    <section class="bg-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
            {{-- Encabezado --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between mb-6">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-1">
                        Catálogo
                    </p>
                    <h1 class="text-2xl sm:text-3xl font-semibold text-white">
                        Llantas disponibles
                    </h1>
                    <p class="text-sm text-slate-400 mt-1">
                        Filtra por medida, marca y características para encontrar la mejor opción.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2 text-xs text-slate-400">
                    @if($width || $profile || $rim)
                        <span class="inline-flex items-center rounded-full bg-slate-900/80 border border-slate-700/70 px-3 py-1">
                            Medida:
                            <span class="ml-1 text-slate-100 font-medium">
                                {{ $width ?? '—' }}/{{ $profile ?? '—' }} R{{ $rim ?? '—' }}
                            </span>
                        </span>
                    @endif

                    @if($brandSlug ?? false)
                        <span class="inline-flex items-center rounded-full bg-slate-900/80 border border-slate-700/70 px-3 py-1">
                            Marca:
                            <span class="ml-1 text-slate-100 font-medium">{{ strtoupper($brandSlug) }}</span>
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-[minmax(0,0.95fr),minmax(0,2.05fr)]">
                {{-- Filtros --}}
                <aside class="space-y-4">
                    <div class="rounded-2xl bg-slate-900/80 border border-slate-800/80 p-4 sm:p-5">
                        <h2 class="text-sm font-semibold text-slate-100 mb-3">Filtros</h2>

                        <form method="GET" action="{{ route('tires.index') }}" class="space-y-4 text-sm">
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label for="width" class="block text-[11px] font-medium text-slate-300 mb-1">Ancho</label>
                                    <input
                                        type="number"
                                        name="width"
                                        id="width"
                                        value="{{ $width }}"
                                        placeholder="Ej: 205"
                                        class="block w-full rounded-xl border-slate-700/80 bg-slate-950/60 text-xs text-slate-100 placeholder:text-slate-500 focus:border-brand focus:ring-brand">
                                </div>
                                <div>
                                    <label for="profile" class="block text-[11px] font-medium text-slate-300 mb-1">Perfil</label>
                                    <input
                                        type="number"
                                        name="profile"
                                        id="profile"
                                        value="{{ $profile }}"
                                        placeholder="Ej: 55"
                                        class="block w-full rounded-xl border-slate-700/80 bg-slate-950/60 text-xs text-slate-100 placeholder:text-slate-500 focus:border-brand focus:ring-brand">
                                </div>
                                <div>
                                    <label for="rim" class="block text-[11px] font-medium text-slate-300 mb-1">Rin</label>
                                    <input
                                        type="number"
                                        name="rim"
                                        id="rim"
                                        value="{{ $rim }}"
                                        placeholder="Ej: 16"
                                        class="block w-full rounded-xl border-slate-700/80 bg-slate-950/60 text-xs text-slate-100 placeholder:text-slate-500 focus:border-brand focus:ring-brand">
                                </div>
                            </div>

                            <div>
                                <label for="brand_slug" class="block text-[11px] font-medium text-slate-300 mb-1">
                                    Marca
                                </label>
                                <select
                                    name="brand_slug"
                                    id="brand_slug"
                                    class="block w-full rounded-xl border-slate-700/80 bg-slate-950/60 text-xs text-slate-100 focus:border-brand focus:ring-brand">
                                    <option value="">Todas</option>
                                    @isset($brands)
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->slug }}" @selected($brandSlug === $brand->slug)>{{ $brand->name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>

                            <div class="flex items-center justify-between gap-2 pt-1">
                                <button type="submit"
                                        class="inline-flex items-center justify-center rounded-xl bg-accent text-slate-950 text-xs font-semibold px-3 py-2 hover:bg-yellow-400 transition">
                                    Aplicar filtros
                                </button>
                                <a href="{{ route('tires.index') }}" class="text-[11px] text-slate-400 hover:text-slate-200">
                                    Limpiar filtros
                                </a>
                            </div>
                        </form>
                    </div>

                    <div class="rounded-2xl bg-slate-900/80 border border-slate-800/80 p-4 text-xs text-slate-400">
                        <h3 class="text-sm font-semibold text-slate-100 mb-2">Tips para elegir tu llanta</h3>
                        <ul class="space-y-1.5">
                            <li>• Respeta siempre la medida recomendada por el fabricante.</li>
                            <li>• Revisa el índice de carga y velocidad para uso en carretera.</li>
                            <li>• Para ciudad, prioriza confort y duración; para carretera, agarre y frenado.</li>
                        </ul>
                    </div>
                </aside>

                {{-- Listado --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-between gap-2 text-xs sm:text-sm text-slate-400">
                        <p>
                            @if ($tires->total() > 0)
                                Mostrando
                                <span class="text-slate-100 font-medium">{{ $tires->firstItem() }}-{{ $tires->lastItem() }}</span>
                                de
                                <span class="text-slate-100 font-medium">{{ $tires->total() }}</span>
                                resultados
                            @else
                                No encontramos llantas con los filtros seleccionados.
                            @endif
                        </p>

                        {{-- Placeholder orden --}}
                        {{-- Cuando definas sort en el backend, ajustas opciones --}}
                        {{-- <div class="flex items-center gap-2">
                            <span class="text-[11px] hidden sm:inline text-slate-500">Ordenar por:</span>
                            <select class="rounded-xl border-slate-700/80 bg-slate-950/60 text-xs text-slate-100 focus:border-brand focus:ring-brand">
                                <option value="relevance">Relevancia</option>
                                <option value="price_asc">Precio: menor a mayor</option>
                                <option value="price_desc">Precio: mayor a menor</option>
                            </select>
                        </div> --}}
                    </div>

                    @if ($tires->count())
                        <div class="grid gap-4 sm:gap-5 md:grid-cols-2 xl:grid-cols-3">
                            @foreach($tires as $tire)
                                @php
                                    /** @var \App\Models\Tire $tire */
                                    $size = $tire->tireSize ?? $tire->size ?? null;
                                    $brand = $tire->brand ?? null;
                                    $effectivePrice = $tire->effective_price ?? $tire->sale_price ?? $tire->base_price;
                                @endphp

                                <article class="group rounded-2xl bg-slate-900/80 border border-slate-800/80 hover:border-brand/80 transition overflow-hidden flex flex-col">
                                    <a href="{{ route('tires.show', $tire->slug) }}" class="block overflow-hidden">
                                        <div class="relative aspect-[4/3]">
                                            @if($tire->image_url)
                                                <img src="{{ $tire->image_url }}"
                                                     alt="{{ $tire->name }}"
                                                     class="absolute inset-0 h-full w-full object-contain bg-slate-950/80 group-hover:scale-[1.03] transition-transform">
                                            @else
                                                <div class="absolute inset-0 flex items-center justify-center bg-slate-950/80">
                                                    <span class="text-[10px] text-slate-500">Imagen de referencia</span>
                                                </div>
                                            @endif

                                            <div class="absolute left-3 top-3 inline-flex items-center rounded-full bg-slate-950/80 backdrop-blur px-2.5 py-1 text-[10px] text-slate-200 border border-slate-800/80">
                                                {{ $size ? "{$size->width}/{$size->aspect_ratio} R{$size->rim_diameter}" : 'Medida N/D' }}
                                            </div>
                                        </div>
                                    </a>

                                    <div class="flex-1 flex flex-col p-3 sm:p-4 gap-3">
                                        <div>
                                            @if($brand)
                                                <p class="text-[11px] uppercase tracking-[0.18em] text-slate-500 mb-0.5">
                                                    {{ $brand->name }}
                                                </p>
                                            @endif
                                            <a href="{{ route('tires.show', $tire->slug) }}"
                                               class="text-sm font-semibold text-slate-100 line-clamp-2 hover:text-accent transition">
                                                {{ $tire->name }}
                                            </a>
                                            @if($tire->usage)
                                                <p class="text-[11px] text-slate-500 mt-0.5">
                                                    Uso: {{ str_replace('_', ' ', strtolower($tire->usage->name ?? $tire->usage)) }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="flex items-end justify-between gap-3">
                                            <div class="space-y-0.5">
                                                @if($tire->sale_price && $tire->sale_price < $tire->base_price)
                                                    <p class="text-xs text-slate-500 line-through">
                                                        $ {{ number_format($tire->base_price, 0, ',', '.') }}
                                                    </p>
                                                    <p class="text-base font-semibold text-accent">
                                                        $ {{ number_format($tire->sale_price, 0, ',', '.') }}
                                                    </p>
                                                @else
                                                    <p class="text-base font-semibold text-accent">
                                                        $ {{ number_format($effectivePrice, 0, ',', '.') }}
                                                    </p>
                                                @endif
                                                <p class="text-[11px] text-slate-400">
                                                    Precio por llanta, incluye instalación básica.
                                                </p>
                                            </div>

                                            <div class="flex flex-col items-end gap-1 text-[10px] text-slate-500">
                                                @if($tire->load_index)
                                                    <span>Índice carga: {{ $tire->load_index }}</span>
                                                @endif
                                                @if($tire->speed_rating)
                                                    <span>Velocidad: {{ $tire->speed_rating }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <form method="POST" action="{{ route('cart.store') }}" class="mt-auto">
                                            @csrf
                                            <input type="hidden" name="cart_token" value="{{ $cartToken }}">
                                            <input type="hidden" name="tire_id" value="{{ $tire->id }}">
                                            <input type="hidden" name="quantity" value="1">

                                            <button
                                                type="submit"
                                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-brand text-white text-xs font-semibold px-3 py-2.5 hover:bg-brand/90 transition">
                                                <span>Agregar al carrito</span>
                                                <span aria-hidden="true">+</span>
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $tires->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="mt-8 rounded-2xl bg-slate-900/80 border border-slate-800/80 p-6 text-sm text-slate-300">
                            No encontramos llantas con los filtros seleccionados. Intenta ampliar la búsqueda cambiando la marca o la medida.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
