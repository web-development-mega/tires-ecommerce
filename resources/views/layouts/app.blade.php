<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>@yield('title', 'Compra llantas en línea')</title>
  <meta name="description" content="@yield('meta_description', 'Encuentra y compra llantas nuevas con instalación en serviteca o domicilio.')">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @else
  <script>
    window.tailwind = window.tailwind || {};
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Instrument Sans"', 'system-ui', 'sans-serif'],
          },
          colors: {
            brand: '#0170A7',
            accent: '#FBBF24',
            ink: '#0F172A',
          },
          boxShadow: {
            card: '0 20px 45px rgba(15,23,42,0.45)',
          },
          borderRadius: {
            '2xl': '1.25rem',
          },
        },
      },
    };
  </script>
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
  @endif

  {{-- Ajustes finos del header para que el panel se vea como en el diseño --}}
  <style>
    #mega-header-bar {
      border-top: 3px solid #5c5c5c;
      border-bottom: 3px solid #5c5c5c;
    }

    /* Panel azul con bisel a la derecha - usando un corte recto inclinado */
    #mega-logo-panel {
      position: relative;
      /* Corte diagonal de arriba derecha a abajo izquierda a 45 grados */
      clip-path: polygon(0 0,
          100% 0,
          calc(100% - 80px) 100%,
          0 100%);
    }

    /* Borde gris diagonal derecho */
    #mega-logo-panel::after {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 3px;
      height: 100%;
      background: #8a8a8a;
      transform-origin: top right;
      transform: skewX(-45deg);
      pointer-events: none;
    }
  </style>

  @stack('head')
</head>

<body class="bg-slate-950 text-slate-100 antialiased">
  <div class="min-h-screen flex flex-col">

    {{-- HEADER --}}
    <header id="mega-header" class="bg-[#3f3f3f] text-white">
      <div class="relative">
        {{-- Barra principal: full width con marco gris --}}
        <div id="mega-header-bar" class="relative bg-[#001326] h-16 md:h-20 flex items-stretch w-full">

          {{-- Panel logo biselado (desktop) --}}
          <a href="{{ route('home') }}" class="relative hidden md:flex items-stretch">
            <div id="mega-logo-panel"
              class="h-full w-[360px] flex items-center justify-center bg-[#00569c]">
              <img
                src="{{ asset('images/logo-megallantas.png') }}" {{-- <- placeholder del logo --}}
                alt="MegaLlantas"
                class="h-16 w-auto object-contain">
            </div>
          </a>

          {{-- Logo móvil como imagen compacta --}}
          <a href="{{ route('home') }}" class="flex md:hidden items-center px-3">
            <img
              src="{{ asset('images/logo-megallantas.png') }}"
              alt="MegaLlantas"
              class="h-8 w-auto object-contain">
          </a>

          {{-- Navegación centro --}}
          <div class="flex-1 flex items-center justify-center px-4">
            {{-- Desktop --}}
            <nav class="hidden md:flex items-center gap-10 text-sm font-black" style="font-family: 'Satoshi', sans-serif;">
              <a href="{{ route('home') }}" class="hover:text-[#FBBF24] transition-colors">
                Inicio
              </a>
              <a href="{{ route('tires.index') }}" class="hover:text-[#FBBF24] transition-colors">
                Catálogo
              </a>
              <a href="{{ url('/#ubicacion') }}" class="hover:text-[#FBBF24] transition-colors">
                Ubicación
              </a>
            </nav>

            {{-- Navegación móvil --}}
            <nav class="flex md:hidden items-center gap-4 text-xs font-black" style="font-family: 'Satoshi', sans-serif;">
              <a href="{{ route('tires.index') }}" class="hover:text-[#FBBF24]">Catálogo</a>
              <a href="{{ url('/#ubicacion') }}" class="hover:text-[#FBBF24]">Ubicación</a>
            </nav>
          </div>

          {{-- Acciones derecha --}}
          <div class="flex items-center gap-3 pr-4">
            {{-- Carrito --}}
            <a href="{{ route('cart.index') }}"
              class="relative inline-flex items-center justify-center h-10 w-10 rounded-full bg-[#0059a6] shadow-[0_10px_25px_rgba(0,0,0,0.4)]">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                class="h-5 w-5">
                <path fill="currentColor"
                  d="M7 18a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm10 0a2 2 0 1 0 .001 3.999A2 2 0 0 0 17 18ZM6.2 6l.3 2h11.3a1 1 0 0 1 .97 1.243l-1.2 4.5A1.5 1.5 0 0 1 16.1 15H9.4a1.5 1.5 0 0 1-1.47-1.157L6 6.5 5.3 4H3a1 1 0 1 1 0-2h3a1 1 0 0 1 .96.73L7.34 6H6.2Z" />
              </svg>
              @if (request()->cookie('cart_token'))
              <span class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-red-500 border-2 border-[#001326]"></span>
              @endif
            </a>

            {{-- Buscador desktop --}}
            <form action="{{ route('tires.index') }}" method="GET"
              class="hidden sm:flex items-center bg-white rounded-full pl-4 pr-3 py-1.5 w-48 lg:w-64">
              <input
                type="search"
                name="q"
                placeholder="Buscar llantas"
                class="flex-1 text-xs text-slate-900 placeholder-slate-400 bg-transparent border-0 focus:ring-0 focus:outline-none">
              <button type="submit" class="flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                  class="h-4 w-4 text-slate-700">
                  <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2" fill="none" />
                  <line x1="16" y1="16" x2="21" y2="21" stroke="currentColor" stroke-width="2" />
                </svg>
              </button>
            </form>
          </div>
        </div>
      </div>
    </header>

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1">
      @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-black text-white" style="font-family: 'Satoshi', sans-serif;">
      <div class="w-full px-6 md:px-12 lg:px-20 py-8 md:py-12">
        <div class="grid gap-8 md:grid-cols-2 lg:gap-16">
          {{-- Enlaces --}}
          <div>
            <h2 class="text-2xl md:text-3xl font-black mb-4 md:mb-6">Enlaces</h2>
            <ul class="space-y-2 text-sm md:text-base font-black">
              <li><a href="{{ route('home') }}" class="hover:text-[#FBBF24] transition-colors">Inicio</a></li>
              <li><a href="#" class="hover:text-[#FBBF24] transition-colors">Sobre nosotros</a></li>
              <li><a href="#" class="hover:text-[#FBBF24] transition-colors">Corporativos</a></li>
              <li><a href="#" class="hover:text-[#FBBF24] transition-colors">Servicios</a></li>
              <li><a href="#" class="hover:text-[#FBBF24] transition-colors">Términos y Condiciones</a></li>
              <li><a href="#" class="hover:text-[#FBBF24] transition-colors">Privacidad</a></li>
              <li><a href="#" class="hover:text-[#FBBF24] transition-colors">Contáctenos</a></li>
            </ul>
          </div>

          {{-- Contáctenos --}}
          <div>
            <h2 class="text-2xl md:text-3xl font-black mb-4 md:mb-6">Contáctenos</h2>
            <div class="space-y-2 text-sm md:text-base font-black">
              <p class="flex items-start gap-2">
                <span class="flex-shrink-0">💬</span>
                <a href="https://wa.me/573164307199" target="_blank" rel="noopener" class="hover:text-[#FBBF24] transition-colors">
                  Contáctenos
                </a>
              </p>
              <p class="flex items-start gap-2">
                <span class="flex-shrink-0">✉️</span>
                <a href="mailto:mercadeo@megatecnicentro.com" class="hover:text-[#FBBF24] transition-colors break-all">
                  mercadeo@megatecnicentro.com
                </a>
              </p>
              <p class="flex items-start gap-2">
                <span class="flex-shrink-0">📞</span>
                <span>(+57) 316 430 7199</span>
              </p>
            </div>

            {{-- Redes sociales --}}
            <div class="mt-6 flex items-center gap-3">
              <a href="#" class="h-10 w-10 rounded-full bg-white text-black flex items-center justify-center hover:bg-[#FBBF24] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                </svg>
              </a>
              <a href="#" class="h-10 w-10 rounded-full bg-white text-black flex items-center justify-center hover:bg-[#FBBF24] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                </svg>
              </a>
              <a href="#" class="h-10 w-10 rounded-full bg-white text-black flex items-center justify-center hover:bg-[#FBBF24] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                </svg>
              </a>
              <a href="#" class="h-10 w-10 rounded-full bg-white text-black flex items-center justify-center hover:bg-[#FBBF24] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" />
                </svg>
              </a>
            </div>
          </div>
        </div>

        {{-- By Grupo Mega --}}
        <div class="mt-8 pt-6 border-t border-gray-800">
          <p class="text-sm md:text-base font-black text-center">By Grupo Mega ®</p>
        </div>
      </div>
    </footer>
  </div>

  @stack('scripts')
</body>

</html>
