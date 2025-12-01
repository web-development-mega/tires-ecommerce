@extends('layouts.app')

@section('title', 'Compra llantas en línea | MegaLlantas')
@section('meta_description', 'Compra llantas en línea y recibe instalación, alineación y balanceo en Medellín y el Valle de Aburrá.')

@section('content')
<section id="hero-promo-alineacion" class="bg-black">
  <div class="relative w-full border-[3px] border-[#5c5c5c] bg-black overflow-hidden">

    {{-- Banner completo tal cual el diseño, sin recortes --}}
    <img
      src="{{ asset('images/banner.jpg') }}"
      alt="Por la compra de 2 llantas recibes gratis alineación y balanceo"
      class="block w-full h-auto select-none pointer-events-none">

    {{-- Bloque de texto alineado a la derecha, sobre el área negra --}}
    <div class="absolute inset-0 flex items-center justify-end pr-[6%] md:pr-[8%] lg:pr-[10%]">
      <div class="text-right max-w-[560px] leading-none">

        {{-- POR LA COMPRA DE --}}
        <p
          class="uppercase mb-1"
          style="
                        font-family: 'Druk Wide Super', system-ui, sans-serif;
                        font-weight: 900;
                        font-style: italic;
                        color: #ffffff;
                        letter-spacing: 0.08em;
                        font-size: clamp(18px, 2.3vw, 24px);
                        line-height: 0.9;
                    ">
          POR LA COMPRA DE
        </p>

        {{-- 2 LLANTAS (una sola línea) --}}
        <p
          class="uppercase mb-2 whitespace-nowrap"
          style="
                        font-family: 'Druk Wide Super', system-ui, sans-serif;
                        font-weight: 900;
                        font-style: italic;
                        color: #ffffff;
                        letter-spacing: 0.05em;
                        font-size: clamp(42px, 5vw, 72px);
                        line-height: 0.8;
                    ">
          2 LLANTAS
        </p>

        {{-- RECIBE GRATIS --}}
        <p
          class="uppercase mt-3 mb-1"
          style="
                        font-family: 'Druk Wide Super', system-ui, sans-serif;
                        font-weight: 900;
                        font-style: italic;
                        color: #62B5E5;
                        letter-spacing: 0.08em;
                        font-size: clamp(18px, 2.3vw, 24px);
                        line-height: 0.9;
                    ">
          RECIBE GRATIS
        </p>

        {{-- ALINEACIÓN Y BALANCEO (una sola línea) --}}
        <p
          class="uppercase mb-4 whitespace-nowrap"
          style="
                        font-family: 'Druk Wide Super', system-ui, sans-serif;
                        font-weight: 900;
                        font-style: italic;
                        color: #62B5E5;
                        letter-spacing: 0.08em;
                        font-size: clamp(18px, 2.3vw, 24px);
                        line-height: 0.9;
                    ">
          ALINEACIÓN Y BALANCEO
        </p>

        {{-- Botón --}}
        <div class="flex justify-end">
          <a
            href="{{ route('tires.index') }}"
            class="inline-flex items-center justify-center rounded-full bg-white px-10 py-3 uppercase hover:bg-slate-100 transition-colors shadow-[0_10px_25px_rgba(0,0,0,0.45)] border border-black btn-satoshi-strong-italic text-black"
            style="font-size: 13px; letter-spacing: 0.09em; color: #000000;">
            COMPRA AHORA
          </a>
        </div>
      </div>
    </div>

    {{-- Términos centrados abajo --}}
    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 px-4">
      <p
        class="uppercase text-center"
        style="
                    font-family: 'Satoshi', system-ui, sans-serif;
                    font-weight: 500;
                    font-size: 9px;
                    letter-spacing: 0.24em;
                    color: #ffffff;
                    opacity: 0.9;
                ">
        *APLICAN TÉRMINOS Y CONDICIONES. PARA MEDELLÍN Y VALLE DE ABURRÁ.
      </p>
    </div>
  </div>
</section>

{{-- ===================================================================== --}}
{{-- Sección: Llantas más vendidas                                         --}}
{{-- ===================================================================== --}}
@if(isset($featuredTires) && $featuredTires->isNotEmpty())
<section id="best-selling-tires" class="bg-[#eef4fb] py-10 md:py-14">
  <div class="max-w-6xl mx-auto px-4">

    {{-- Tabs superiores --}}
    <div class="flex flex-wrap gap-4 justify-center md:justify-between mb-7">
      <button
        class="px-10 py-3 rounded-2xl bg-[#001939] text-white text-sm font-semibold tracking-[0.12em] uppercase shadow-[0_12px_30px_rgba(15,23,42,0.45)]">
        Llantas Auto
      </button>
      <button
        class="px-10 py-3 rounded-2xl bg-white text-[#001939] text-sm font-semibold tracking-[0.12em] uppercase border border-slate-300">
        Llantas Camioneta
      </button>
      <button
        class="px-10 py-3 rounded-2xl bg-white text-[#001939] text-sm font-semibold tracking-[0.12em] uppercase border border-slate-300">
        Llantas Camión
      </button>
      <button
        class="px-10 py-3 rounded-2xl bg-white text-[#001939] text-sm font-semibold tracking-[0.12em] uppercase border border-slate-300">
        Llantas Tractomula
      </button>
    </div>

    {{-- Contenedor principal --}}
    <div class="bg-[#f7f9fe] rounded-[32px] shadow-[0_22px_50px_rgba(15,23,42,0.35)] px-6 md:px-10 py-8 md:py-10">
      <div class="flex flex-col lg:flex-row gap-6 md:gap-10 items-start">

        {{-- Columna izquierda: icono + título --}}
        <div class="w-full lg:w-auto flex lg:flex-col items-center lg:items-start gap-4 lg:gap-3 mb-4 lg:mb-0">
          <div class="flex items-center gap-3">
            <div class="h-11 w-11 rounded-full bg-white shadow-sm flex items-center justify-center">
              <img src="{{ asset('images/wheel.png') }}"
                alt="Ícono llanta"
                class="h-8 w-8 object-contain">
            </div>
            <div class="lg:hidden">
              <p class="text-xs uppercase tracking-[0.20em] text-[#003b73]">Llantas</p>
              <p class="text-lg font-extrabold text-[#001939] leading-tight">Más Vendidas</p>
            </div>
          </div>
          <div class="hidden lg:block">
            <p class="text-xs uppercase tracking-[0.20em] text-[#003b73]">Llantas</p>
            <p class="text-xl font-extrabold text-[#001939] leading-tight">Más Vendidas</p>
          </div>
        </div>

        {{-- Columna derecha: cards de productos --}}
        <div class="flex-1">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 md:gap-5 items-stretch">
            @foreach($featuredTires->take(5) as $tire)
            @php
            $size = $tire->tireSize;
            $sizeLabel = $size
            ? $size->width . '/' . $size->aspect_ratio . 'R' . $size->rim_diameter
            : null;

            $loadSpeed = trim(($tire->load_index ? $tire->load_index : '') . ' ' . ($tire->speed_rating ?? ''));
            $specLabel = trim($sizeLabel . ' ' . $loadSpeed);

            $imageUrl = $tire->image_url ?: asset('images/placeholder-tire.png');

            $hasDiscount = $tire->sale_price && $tire->base_price && $tire->sale_price < $tire->base_price;
              $discountPct = $hasDiscount
              ? max(1, round(100 - ($tire->sale_price / $tire->base_price * 100)))
              : null;
              @endphp

              <article class="bg-white rounded-3xl px-4 pt-5 pb-4 shadow-[0_14px_32px_rgba(15,23,42,0.18)] flex flex-col h-full">
                {{-- Zona superior: imagen + textos + estrellas --}}
                <div class="flex-1 flex flex-col items-center text-center">
                  {{-- Imagen + badge --}}
                  <div class="relative mb-4 h-28 md:h-32 w-full flex items-center justify-center">
                    <img
                      src="{{ $imageUrl }}"
                      alt="{{ $tire->name }}"
                      class="max-h-full w-auto object-contain mx-auto">

                    @if($discountPct)
                    <div
                      class="absolute -top-2 -right-2 h-10 w-10 rounded-full bg-[#003b73] text-white flex flex-col items-center justify-center text-[10px] leading-tight font-bold">
                      <span>{{ $discountPct }}%</span>
                      <span>DCTO</span>
                    </div>
                    @endif
                  </div>

                  {{-- Nombre --}}
                  <h3 class="text-[11px] font-extrabold uppercase tracking-[0.08em] text-[#003b73] leading-snug">
                    {{ \Illuminate\Support\Str::upper(optional($tire->brand)->name) }}<br>
                    {{ \Illuminate\Support\Str::upper($tire->name) }}
                  </h3>

                  {{-- Medida --}}
                  @if($specLabel)
                  <p class="mt-1 text-[11px] text-slate-500">
                    {{ $specLabel }}
                  </p>
                  @endif

                  {{-- Estrellas (placeholder estático) --}}
                  <div class="mt-2 flex items-center justify-center gap-[2px] text-[#fbbf24] text-xs">
                    @for ($i = 0; $i < 4; $i++)
                      <span>★</span>
                      @endfor
                      <span class="text-slate-300">★</span>
                  </div>
                </div>

                {{-- Zona inferior: CTA alineado al fondo --}}
                <div class="mt-3 flex justify-center">
                  <a href="{{ route('tires.show', $tire->slug) }}"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-[#001939] text-[11px] font-semibold text-white tracking-[0.12em] uppercase hover:bg-[#00306f] transition-colors">
                    COMPRA AHORA
                  </a>
                </div>
              </article>
              @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endif

{{-- ===================================================================== --}}
{{-- Sección: Marcas destacadas                                            --}}
{{-- ===================================================================== --}}
@php
// Usar la colección que tengas disponible: $brandLogos o $brands
$brandsCollection = $brandLogos ?? $brands ?? null;
@endphp

<section id="brands-strip" class="bg-slate-100 py-10 md:py-12">
  <div class="max-w-6xl mx-auto px-4">

    {{-- Título siempre visible --}}
    <div class="text-center">
      <h2 class="text-[18px] md:text-[22px] lg:text-[24px] font-semibold tracking-[0.18em] uppercase text-[#003b73] leading-tight">
        MANEJAMOS LAS <span class="font-extrabold">MEJORES MARCAS</span><br>
        A LOS <span class="font-extrabold">MEJORES PRECIOS</span>
      </h2>

      <div class="mt-3 w-24 h-[3px] bg-[#001939] mx-auto rounded-full"></div>
    </div>

    {{-- Logos de marcas (solo si hay datos) --}}
    @if($brandsCollection && $brandsCollection->isNotEmpty())
    <div class="mt-8 flex flex-wrap items-center justify-center gap-x-12 gap-y-8">
      @foreach($brandsCollection->take(8) as $brand)
      @php
      $logo = $brand->logo_url ?? $brand->image_url ?? null;
      $logo = $logo ?: asset('images/placeholder-brand.png');
      @endphp

      <div class="w-32 h-20 md:w-36 md:h-24 lg:w-40 lg:h-28 flex items-center justify-center">
        <img
          src="{{ $logo }}"
          alt="{{ $brand->name }}"
          title="{{ $brand->name }}"
          class="max-w-full max-h-full object-contain grayscale-[35%] hover:grayscale-0 transition duration-200"
          onerror="this.onerror=null; this.src='{{ asset('images/placeholder-brand.png') }}'; console.error('Error cargando logo:', '{{ $brand->name }}', this.src);">
      </div>
      @endforeach
    </div>
    @endif

  </div>
</section>



@endsection
