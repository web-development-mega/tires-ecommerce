@extends('layouts.app')

@section('title', 'Finaliza tu compra')
@section('meta_description', 'Ingresa tus datos, elige tipo de entrega y agenda la instalación de tus llantas.')

@section('content')
    @php
        /** @var \App\Models\Cart|null $cart */
        /** @var \Illuminate\Support\Collection|\App\Models\ServiceLocation[]|null $serviceLocations */
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
                    <h1 class="text-2xl sm:text-3xl font-semibold text-white">Finaliza tu compra</h1>
                    <p class="text-sm text-slate-400">
                        Diligencia tus datos y elige si quieres instalación en serviteca o entrega a domicilio.
                    </p>
                </div>
            </div>

            @if (! $cart || $items->isEmpty())
                <div class="rounded-2xl bg-slate-900/80 border border-slate-800/80 p-6 text-sm text-slate-300 space-y-3">
                    <p class="font-medium text-slate-100">No tienes un carrito activo.</p>
                    <p class="text-slate-400">
                        Agrega llantas al carrito antes de continuar al checkout.
                    </p>
                    <a href="{{ route('tires.index') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-accent text-slate-950 text-sm font-semibold px-4 py-2.5 hover:bg-yellow-400 transition">
                        Ir al catálogo
                    </a>
                </div>
            @else
                <div class="grid gap-6 lg:grid-cols-[minmax(0,1.7fr),minmax(0,1.1fr)] items-start">
                    {{-- Formulario checkout --}}
                    <form method="POST" action="{{ route('checkout.store') }}"
                          class="space-y-6 rounded-2xl bg-slate-900/80 border border-slate-800/80 p-4 sm:p-5">
                        @csrf
                        <input type="hidden" name="cart_token" value="{{ $cartToken }}">

                        {{-- Datos cliente --}}
                        <div class="space-y-3">
                            <h2 class="text-sm font-semibold text-slate-100">Datos del cliente</h2>

                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label for="customer_first_name" class="block text-xs font-medium text-slate-300 mb-1">
                                        Nombres *
                                    </label>
                                    <input
                                        type="text"
                                        name="customer_first_name"
                                        id="customer_first_name"
                                        required
                                        autocomplete="given-name"
                                        class="block w-full rounded-xl border-slate-700/80 bg-slate-950/60 text-sm text-slate-100 focus:border-brand focus:ring-brand">
                                </div>
                                <div>
                                    <label for="customer_last_name" class="block text-xs font-medium text-slate-300 mb-1">
                                        Apellidos *
                                    </label>
                                    <input
                                        type="text"
                                        name="customer_last_name"
                                        id="customer_last_name"
                                        required
                                        autocomplete="family-name"
                                        class="block w-full rounded-xl border-slate-700/80 bg-slate-950/60 text-sm text-slate-100 focus:border-brand focus:ring-brand">
                                </div>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label for="customer_email" class="block text-xs font-medium text-slate-300 mb-1">
                                        Correo electrónico *
                                    </label>
                                    <input
                                        type="email"
                                        name="customer_email"
                                        id="customer_email"
                                        required
                                        autocomplete="email"
                                        class="block w-full rounded-xl border-slate-700/80 bg-slate-950/60 text-sm text-slate-100 focus:border-brand focus:ring-brand">
                                </div>
                                <div>
                                    <label for="customer_phone" class="block text-xs font-medium text-slate-300 mb-1">
                                        Celular / WhatsApp *
                                    </label>
                                    <input
                                        type="tel"
                                        name="customer_phone"
                                        id="customer_phone"
                                        required
                                        autocomplete="tel"
                                        class="block w-full rounded-xl border-slate-700/80 bg-slate-950/60 text-sm text-slate-100 focus:border-brand focus:ring-brand">
                                </div>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-[0.6fr,1.4fr]">
                                <div>
                                    <label for="document_type" class="block text-xs font-medium text-slate-300 mb-1">
                                        Tipo de documento
                                    </label>
                                    <select
                                        name="document_type"
                                        id="document_type"
                                        class="block w-full rounded-xl border-slate-700/80 bg-slate-950/60 text-xs text-slate-100 focus:border-brand focus:ring-brand">
                                        <option value="">Selecciona</option>
                                        <option value="CC">Cédula</option>
                                        <option value="NIT">NIT</option>
                                        <option value="CE">Cédula extranjería</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="document_number" class="block text-xs font-medium text-slate-300 mb-1">
                                        Número de documento
                                    </label>
                                    <input
                                        type="text"
                                        name="document_number"
                                        id="document_number"
                                        class="block w-full rounded-xl border-slate-700/80 bg-slate-950/60 text-sm text-slate-100 focus:border-brand focus:ring-brand">
                                </div>
                            </div>
                        </div>

                        {{-- Tipo de entrega --}}
                        <div class="space-y-3">
                            <h2 class="text-sm font-semibold text-slate-100">Entrega e instalación</h2>
                            <p class="text-xs text-slate-400">
                                Elige si deseas instalar en una serviteca aliada o recibir las llantas a domicilio (según cobertura).
                            </p>

                            <div class="grid gap-3 sm:grid-cols-2 text-xs">
                                <label class="flex items-start gap-3 rounded-2xl border border-slate-700/80 bg-slate-950/60 px-3 py-3 cursor-pointer hover:border-brand/80 transition">
                                    <input
                                        type="radio"
                                        name="delivery_type"
                                        value="service_location"
                                        class="mt-1 h-4 w-4 text-brand border-slate-600 bg-slate-900"
                                        checked>
                                    <span class="space-y-1">
                                        <span class="block text-slate-100 font-semibold text-sm">Instalación en serviteca</span>
                                        <span class="block text-slate-400">
                                            Elige una sede y agenda la instalación según disponibilidad.
                                        </span>
                                    </span>
                                </label>

                                <label class="flex items-start gap-3 rounded-2xl border border-slate-700/80 bg-slate-950/60 px-3 py-3 cursor-pointer hover:border-brand/80 transition">
                                    <input
                                        type="radio"
                                        name="delivery_type"
                                        value="home_delivery"
                                        class="mt-1 h-4 w-4 text-brand border-slate-600 bg-slate-900">
                                    <span class="space-y-1">
                                        <span class="block text-slate-100 font-semibold text-sm">Entrega a domicilio</span>
                                        <span class="block text-slate-400">
                                            Según cobertura y condiciones de envío. Coordinaremos por WhatsApp.
                                        </span>
                                    </span>
                                </label>
                            </div>

                            @if(isset($serviceLocations) && $serviceLocations->count())
                                <div class="space-y-2">
                                    <label for="service_location_id" class="block text-xs font-medium text-slate-300 mb-1">
                                        Serviteca para instalación
                                    </label>
                                    <select
                                        name="service_location_id"
                                        id="service_location_id"
                                        class="block w-full rounded-xl border-slate-700/80 bg-slate-950/60 text-sm text-slate-100 focus:border-brand focus:ring-brand">
                                        <option value="">Selecciona una serviteca</option>
                                        @foreach($serviceLocations as $location)
                                            <option value="{{ $location->id }}">
                                                {{ $location->name }} — {{ $location->municipality_name ?? $location->municipality_slug }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-[11px] text-slate-500">
                                        El detalle de la agenda (día y franja horaria) se confirmará después del pago.
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- Dirección (para domicilio o facturación) --}}
                        <div class="space-y-3">
                            <h2 class="text-sm font-semibold text-slate-100">Dirección (domicilio / facturación)</h2>

                            <div class="space-y-3 text-sm">
                                <div>
                                    <label for="shipping_address_line1" class="block text-xs font-medium text-slate-300 mb-1">
                                        Dirección
                                    </label>
                                    <input
                                        type="text"
                                        name="shipping_address_line1"
                                        id="shipping_address_line1"
                                        class="block w-full rounded-xl border-slate-700/80 bg-slate-950/60 text-sm text-slate-100 focus:border-brand focus:ring-brand">
                                </div>
                                <div>
                                    <label for="shipping_address_line2" class="block text-xs font-medium text-slate-300 mb-1">
                                        Complemento (apto, torre, referencia)
                                    </label>
                                    <input
                                        type="text"
                                        name="shipping_address_line2"
                                        id="shipping_address_line2"
                                        class="block w-full rounded-xl border-slate-700/80 bg-slate-950/60 text-sm text-slate-100 focus:border-brand focus:ring-brand">
                                </div>
                                <div class="grid gap-3 sm:grid-cols-3">
                                    <div>
                                        <label for="shipping_city" class="block text-xs font-medium text-slate-300 mb-1">
                                            Ciudad
                                        </label>
                                        <input
                                            type="text"
                                            name="shipping_city"
                                            id="shipping_city"
                                            value="Medellín"
                                            class="block w-full rounded-xl border-slate-700/80 bg-slate-950/60 text-sm text-slate-100 focus:border-brand focus:ring-brand">
                                    </div>
                                    <div>
                                        <label for="shipping_state" class="block text-xs font-medium text-slate-300 mb-1">
                                            Departamento
                                        </label>
                                        <input
                                            type="text"
                                            name="shipping_state"
                                            id="shipping_state"
                                            value="Antioquia"
                                            class="block w-full rounded-xl border-slate-700/80 bg-slate-950/60 text-sm text-slate-100 focus:border-brand focus:ring-brand">
                                    </div>
                                    <div>
                                        <label for="shipping_postal_code" class="block text-xs font-medium text-slate-300 mb-1">
                                            Código postal
                                        </label>
                                        <input
                                            type="text"
                                            name="shipping_postal_code"
                                            id="shipping_postal_code"
                                            class="block w-full rounded-xl border-slate-700/80 bg-slate-950/60 text-sm text-slate-100 focus:border-brand focus:ring-brand">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Notas --}}
                        <div class="space-y-3">
                            <h2 class="text-sm font-semibold text-slate-100">Notas adicionales</h2>
                            <textarea
                                name="meta[notes]"
                                rows="3"
                                class="block w-full rounded-xl border-slate-700/80 bg-slate-950/60 text-sm text-slate-100 focus:border-brand focus:ring-brand"
                                placeholder="Comparte detalles útiles para la instalación (ej. parqueadero, restricciones de acceso, horarios preferidos, etc.)"></textarea>
                        </div>

                        <div class="pt-2">
                            <button
                                type="submit"
                                class="w-full inline-flex items-center justify-center rounded-xl bg-accent text-slate-950 text-sm font-semibold px-4 py-2.5 hover:bg-yellow-400 transition">
                                Continuar al pago seguro
                            </button>
                            <p class="mt-2 text-[11px] text-slate-500">
                                En el siguiente paso serás redirigido a la pasarela de pagos para completar la compra.
                            </p>
                        </div>
                    </form>

                    {{-- Resumen pedido --}}
                    <aside class="rounded-2xl bg-slate-900/80 border border-slate-800/80 p-4 sm:p-5 space-y-4 text-sm">
                        <h2 class="text-sm font-semibold text-slate-100">Resumen del pedido</h2>

                        <div class="space-y-3 max-h-[260px] overflow-auto pr-1">
                            @foreach($items as $item)
                                @php
                                    $tire = $item->buyable;
                                    if (! $tire) { continue; }
                                    $size = $tire->tireSize ?? $tire->size ?? null;
                                    $unitPrice = $tire->effective_price ?? $tire->sale_price ?? $tire->base_price ?? 0;
                                @endphp
                                <div class="flex justify-between gap-3">
                                    <div class="flex-1">
                                        <p class="text-xs font-medium text-slate-100 line-clamp-2">
                                            {{ $tire->name }}
                                        </p>
                                        <p class="text-[11px] text-slate-500">
                                            x {{ $item->quantity }}
                                            @if($size)
                                                · {{ $size->width }}/{{ $size->aspect_ratio }} R{{ $size->rim_diameter }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="text-right text-xs text-slate-200">
                                        $ {{ number_format($unitPrice * $item->quantity, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @php
                            $shipping = 0;
                            $tax = 0;
                            $grand = $subtotal + $shipping + $tax;
                        @endphp

                        <dl class="space-y-1.5 text-sm pt-2 border-t border-slate-800/80">
                            <div class="flex justify-between">
                                <dt class="text-slate-400">Subtotal productos</dt>
                                <dd class="text-slate-100">
                                    $ {{ number_format($subtotal, 0, ',', '.') }}
                                </dd>
                            </div>
                            <div class="flex justify-between text-xs">
                                <dt class="text-slate-500">Envío / instalación</dt>
                                <dd class="text-slate-500">Se calcula según tu selección</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-slate-300">Total estimado</dt>
                                <dd class="text-accent font-semibold">
                                    $ {{ number_format($grand, 0, ',', '.') }}
                                </dd>
                            </div>
                        </dl>

                        <p class="text-[11px] text-slate-500">
                            El valor final puede ajustarse al momento del cobro en la pasarela de pagos según los servicios adicionales seleccionados.
                        </p>
                    </aside>
                </div>
            @endif
        </div>
    </section>
@endsectionio
