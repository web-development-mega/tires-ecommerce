@extends('layouts.app')

@section('title', 'Tu carrito de llantas')
@section('meta_description', 'Revisa las llantas agregadas a tu carrito, ajusta cantidades y continúa al checkout.')

@section('content')
    @php
        /** @var \App\Models\Cart|null $cart */
        $cartToken = request()->cookie('cart_token');
        $items = $cart?->items ?? collect();
        $subtotal = 0;

        foreach ($items as $item) {
            $buyable = $item->buyable;
            if (! $buyable) {
                continue;
            }
            $price = $buyable->effective_price ?? $buyable->sale_price ?? $buyable->base_price ?? 0;
            $subtotal += $price * $item->quantity;
        }
    @endphp

    <section class="bg-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-semibold text-white">Tu carrito</h1>
                    <p class="text-sm text-slate-400">
                        Revisa las llantas seleccionadas antes de continuar con la compra.
                    </p>
                </div>
            </div>

            @if (! $cart || $items->isEmpty())
                <div class="rounded-2xl bg-slate-900/80 border border-slate-800/80 p-6 text-sm text-slate-300 space-y-3">
                    <p class="font-medium text-slate-100">Tu carrito está vacío.</p>
                    <p class="text-slate-400">
                        Busca la medida de tus llantas y agrega productos para continuar al checkout.
                    </p>
                    <a href="{{ route('tires.index') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-accent text-slate-950 text-sm font-semibold px-4 py-2.5 hover:bg-yellow-400 transition">
                        Ir al catálogo
                    </a>
                </div>
            @else
                <div class="grid gap-6 lg:grid-cols-[minmax(0,1.7fr),minmax(0,1.1fr)] items-start">
                    {{-- Lista de items --}}
                    <div class="space-y-3">
                        @foreach($items as $item)
                            @php
                                /** @var \App\Models\CartItem $item */
                                $tire = $item->buyable;
                                if (! $tire) {
                                    continue;
                                }
                                $size = $tire->tireSize ?? $tire->size ?? null;
                                $brand = $tire->brand ?? null;
                                $unitPrice = $tire->effective_price ?? $tire->sale_price ?? $tire->base_price ?? 0;
                                $rowTotal = $unitPrice * $item->quantity;
                            @endphp

                            <article class="rounded-2xl bg-slate-900/80 border border-slate-800/80 p-4 sm:p-5 flex gap-4 sm:gap-5">
                                <div class="hidden sm:flex h-24 w-24 rounded-xl bg-slate-950/80 items-center justify-center overflow-hidden">
                                    @if($tire->image_url)
                                        <img src="{{ $tire->image_url }}"
                                             alt="{{ $tire->name }}"
                                             class="max-h-full max-w-full object-contain">
                                    @else
                                        <span class="text-[11px] text-slate-500 text-center px-2">
                                            Imagen no disponible
                                        </span>
                                    @endif
                                </div>

                                <div class="flex-1 flex flex-col gap-2">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                                        <div>
                                            @if($brand)
                                                <p class="text-[11px] uppercase tracking-[0.18em] text-slate-500 mb-0.5">
                                                    {{ $brand->name }}
                                                </p>
                                            @endif
                                            <a href="{{ route('tires.show', $tire->slug) }}"
                                               class="text-sm sm:text-base font-semibold text-slate-100 hover:text-accent transition">
                                                {{ $tire->name }}
                                            </a>
                                            @if($size)
                                                <p class="text-xs text-slate-400 mt-0.5">
                                                    Medida: {{ $size->width }}/{{ $size->aspect_ratio }} R{{ $size->rim_diameter }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="text-right text-sm">
                                            <p class="text-xs text-slate-400">Precio unidad</p>
                                            <p class="text-sm font-semibold text-accent">
                                                $ {{ number_format($unitPrice, 0, ',', '.') }}
                                            </p>
                                            <p class="text-[11px] text-slate-500">
                                                Subtotal: $ {{ number_format($rowTotal, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap items-center justify-between gap-3 mt-1">
                                        <form method="POST" action="{{ route('cart.update', $item->id) }}" class="flex items-center gap-2 text-xs">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="cart_token" value="{{ $cartToken }}">

                                            <label for="quantity_{{ $item->id }}" class="text-slate-300">
                                                Cantidad:
                                            </label>
                                            <input
                                                type="number"
                                                id="quantity_{{ $item->id }}"
                                                name="quantity"
                                                min="1"
                                                max="8"
                                                value="{{ $item->quantity }}"
                                                class="w-20 rounded-xl border-slate-700/80 bg-slate-950/60 text-xs text-slate-100 focus:border-brand focus:ring-brand">
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center rounded-xl border border-slate-700/80 px-3 py-1.5 text-[11px] text-slate-200 hover:border-brand/80 hover:text-white transition">
                                                Actualizar
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('cart.destroy', $item->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center rounded-xl bg-slate-800/80 px-3 py-1.5 text-[11px] text-slate-300 hover:bg-red-500/10 hover:text-red-300 hover:border-red-500/60 border border-slate-700/80 transition">
                                                Quitar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- Resumen --}}
                    <aside class="rounded-2xl bg-slate-900/80 border border-slate-800/80 p-4 sm:p-5 space-y-4">
                        <h2 class="text-sm font-semibold text-slate-100">
                            Resumen de compra
                        </h2>

                        <dl class="space-y-1.5 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-slate-400">Subtotal</dt>
                                <dd class="text-slate-100">
                                    $ {{ number_format($subtotal, 0, ',', '.') }}
                                </dd>
                            </div>
                            <div class="flex justify-between text-xs">
                                <dt class="text-slate-500">Instalación / envío</dt>
                                <dd class="text-slate-500">
                                    Se calcula según serviteca o domicilio en el checkout.
                                </dd>
                            </div>
                        </dl>

                        <div class="border-t border-slate-800/80 pt-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-300">Total estimado</span>
                                <span class="text-accent font-semibold">
                                    $ {{ number_format($subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1">
                                El valor final puede variar si aplican servicios adicionales (alineación, balanceo especial, etc.).
                            </p>
                        </div>

                        <a href="{{ route('checkout.index') }}"
                           class="w-full inline-flex items-center justify-center rounded-xl bg-accent text-slate-950 text-sm font-semibold px-4 py-2.5 hover:bg-yellow-400 transition">
                            Continuar al checkout
                        </a>

                        <a href="{{ route('tires.index') }}"
                           class="w-full inline-flex items-center justify-center rounded-xl border border-slate-700/80 text-slate-200 text-xs px-4 py-2 hover:border-brand/70 hover:text-white transition">
                            Seguir comprando
                        </a>
                    </aside>
                </div>
            @endif
        </div>
    </section>
@endsection
