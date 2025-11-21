# 🚗 Tires E-Commerce API

API REST backend para plataforma de e-commerce de llantas y servicios automotrices. Desarrollada con Laravel 12 y PHP 8.2.

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=flat&logo=php)](https://php.net)

## 📋 Tabla de Contenidos

- [Características](#-características)
- [Arquitectura](#-arquitectura)
- [Requisitos](#-requisitos)
- [Instalación](#-instalación)
- [Configuración](#-configuración)
- [Base de Datos](#-base-de-datos)
- [API Endpoints](#-api-endpoints)
- [Modelos y Relaciones](#-modelos-y-relaciones)
- [Servicios](#-servicios)
- [Testing](#-testing)
- [Despliegue](#-despliegue)

## ✨ Características

### Catálogo de Llantas
- ✅ Búsqueda de llantas por vehículo (marca, línea, versión, año)
- ✅ Búsqueda de llantas por medida (ancho/perfil/rin)
- ✅ Filtros avanzados: marca, precio, índice de carga, velocidad, tipo de uso
- ✅ Compatibilidad con múltiples posiciones (delantera/trasera/ambas/repuesto)
- ✅ Soporte para características especiales (runflat, all-terrain, highway, winter, summer)

### Gestión de Vehículos
- ✅ Catálogo jerárquico: marca → línea → versión
- ✅ Múltiples tipos de vehículos (auto, SUV, pickup, van, camión, bus, moto, ATV, UTV)
- ✅ Fitments OEM y alternativos
- ✅ Metadatos: motor, combustible, carrocería

### Carrito de Compras
- ✅ Carrito persistente con token único
- ✅ Soporte para usuarios anónimos y autenticados
- ✅ Gestión de items (agregar, actualizar, eliminar)
- ✅ Cálculo automático de totales
- ✅ Carrito polimórfico (preparado para múltiples tipos de productos)

### Sistema de Órdenes
- ✅ Creación de órdenes desde carrito
- ✅ Checkout con validación de datos
- ✅ Tipos de entrega: domicilio, instalación en taller, recoger en tienda
- ✅ Integración con talleres/puntos de servicio
- ✅ Estados de orden: pendiente, confirmada, procesando, completada, cancelada

### Pagos
- ✅ Integración con Wompi (pasarela de pagos)
- ✅ Múltiples métodos: tarjeta, PSE, Nequi, Bancolombia
- ✅ Webhook para confirmación de pagos
- ✅ Transacciones auditables
- ✅ Estados: pendiente, aprobado, rechazado, error

### Autenticación
- ✅ Registro y login de usuarios
- ✅ Autenticación con Laravel Sanctum
- ✅ Tokens API para aplicaciones SPA/móviles

### Puntos de Servicio
- ✅ Gestión de talleres y puntos de instalación
- ✅ Filtrado por municipio del Área Metropolitana de Medellín
- ✅ Tipos de servicio (instalación, alineación, balanceo, etc.)
- ✅ Geolocalización (lat/lng)

### Gestión B2B (Empresas y Flotas)
- ✅ Gestión de empresas corporativas (flotas, distribuidores, otros)
- ✅ Contactos empresariales con roles
- ✅ Gestión de flotas vehiculares
- ✅ Vehículos de flota con placas, VIN y alias
- ✅ Contratos empresariales con estados
- ✅ Reglas de precios especiales por contrato
- ✅ Descuentos por volumen y tipo de producto
- ✅ Precios diferenciados por: producto, marca, categoría, medida
- ✅ Límites de crédito y términos de pago
- ✅ Vigencia de contratos y reglas de precio

### Catálogo Público de Llantas
- ✅ Listado público con filtros básicos
- ✅ Búsqueda por marca (ID o slug)
- ✅ Filtros por medida (ancho/perfil/rin)
- ✅ Filtros por características (runflat, all-terrain)
- ✅ Vista detallada de producto

## 🏗️ Arquitectura

### Estructura del Proyecto

```
app/
├── Enums/              # Enumeraciones PHP 8.2
│   ├── CartStatus.php
│   ├── CompanyType.php
│   ├── CompanyContractStatus.php
│   ├── FitmentPosition.php
│   ├── OrderStatus.php
│   ├── PaymentStatus.php
│   ├── PriceAdjustmentType.php
│   ├── PriceTargetType.php
│   ├── TireUsage.php
│   └── VehicleType.php
├── Http/
│   ├── Controllers/Api/
│   │   ├── AuthController.php
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   ├── OrderPaymentController.php
│   │   ├── ServiceLocationController.php
│   │   ├── TireController.php
│   │   ├── TireSearchController.php
│   │   └── WompiWebhookController.php
│   ├── Requests/       # Form Request Validation
│   └── Resources/      # API Resources (JSON transformers)
├── Models/            # Eloquent Models
│   ├── Brand.php
│   ├── Cart.php
│   ├── CartItem.php
│   ├── Company.php
│   ├── CompanyContact.php
│   ├── CompanyContract.php
│   ├── CompanyPriceRule.php
│   ├── Fleet.php
│   ├── FleetVehicle.php
│   ├── Order.php
│   ├── Payment.php
│   ├── ServiceLocation.php
│   ├── Tire.php
│   ├── TireSize.php
│   ├── Vehicle.php
│   ├── VehicleBrand.php
│   ├── VehicleLine.php
│   ├── VehicleVersion.php
│   └── VehicleTireFitment.php
└── Services/          # Lógica de negocio
    ├── Cart/
    │   └── CartService.php
    ├── Catalog/
    │   └── TireSearchService.php
    ├── Checkout/
    │   └── CheckoutService.php
    └── Payment/
        └── WompiService.php
```

### Patrones de Diseño

- **Service Layer**: Lógica de negocio encapsulada en servicios reutilizables
- **Repository Pattern**: Modelos Eloquent con scopes para consultas complejas
- **Form Request Validation**: Validación centralizada y reutilizable
- **API Resources**: Transformación consistente de respuestas JSON
- **Enums**: Type-safe constants con PHP 8.2 Enums

## 📦 Requisitos

- **PHP**: >= 8.2
- **Composer**: >= 2.0
- **Base de datos**: SQLite (desarrollo) / MySQL/PostgreSQL (producción)
- **Node.js**: >= 18.x (para assets frontend)
- **Extensiones PHP**:
  - PDO
  - mbstring
  - OpenSSL
  - JSON
  - cURL

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/web-development-mega/tires-ecommerce.git
cd tires-ecommerce
```

### 2. Instalar dependencias

```bash
composer install
npm install
```

### 3. Configurar entorno

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar base de datos

Edita `.env` con tus credenciales:

```env
DB_CONNECTION=sqlite
# Para SQLite (desarrollo):
DB_DATABASE=/ruta/absoluta/database/database.sqlite

# Para MySQL (producción):
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=tires_ecommerce
# DB_USERNAME=root
# DB_PASSWORD=
```

### 5. Ejecutar migraciones y seeders

```bash
php artisan migrate:fresh --seed --seeder=TiresCatalogSeeder
```

Esto creará:
- ✅ 3 marcas de llantas (Michelin, Goodyear, Bridgestone)
- ✅ 4 llantas de ejemplo
- ✅ 2 vehículos de prueba (Chevrolet Spark 2020, Renault Duster 2021)
- ✅ Fitments asociados

### 6. Iniciar servidor de desarrollo

```bash
php artisan serve
```

La API estará disponible en: `http://localhost:8000`

## ⚙️ Configuración

### Variables de Entorno Principales

```env
APP_NAME="Megallantas E-Commerce"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Wompi Payment Gateway
WOMPI_PUBLIC_KEY=pub_test_xxxxx
WOMPI_PRIVATE_KEY=prv_test_xxxxx
WOMPI_EVENTS_SECRET=xxxxx
WOMPI_WEBHOOK_URL="${APP_URL}/api/payments/wompi/webhook"

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000
SESSION_DRIVER=file

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@megallantas.com
MAIL_FROM_NAME="${APP_NAME}"
```

## 💾 Base de Datos

### Diagrama ER (Simplificado)

```
brands ──< tires >── tire_sizes >──┐
                                    │
vehicle_brands ──< vehicle_lines ──< vehicle_versions
       │              │
       └──< vehicles ─┴──< vehicle_tire_fitments ──┘
                │
                └──< orders ──< order_items
                       │
                       └──< payments ──< payment_transactions

carts ──< cart_items >── (polymorphic: tires)

service_locations >──< service_types

companies ──< company_contacts
    │
    ├──< fleets ──< fleet_vehicles >── vehicles
    │
    └──< company_contracts ──< company_price_rules
```

### Migraciones Principales

| Tabla | Descripción |
|-------|-------------|
| `brands` | Marcas de llantas (Michelin, Goodyear, etc.) |
| `tire_sizes` | Medidas de llantas (205/55R16, etc.) |
| `tires` | Catálogo de llantas con especificaciones |
| `vehicle_brands` | Marcas de vehículos (Chevrolet, Renault, etc.) |
| `vehicle_lines` | Líneas de vehículos (Spark, Duster, etc.) |
| `vehicle_versions` | Versiones específicas (GT 1.4L, etc.) |
| `vehicles` | Vehículos con año y tipo |
| `vehicle_tire_fitments` | Compatibilidad vehículo-llanta |
| `carts` | Carritos de compra |
| `cart_items` | Items en el carrito (polimórfico) |
| `orders` | Órdenes de compra |
| `order_items` | Productos en la orden |
| `payments` | Pagos asociados a órdenes |
| `payment_transactions` | Transacciones de pago |
| `service_locations` | Talleres y puntos de servicio |
| `companies` | Empresas B2B (flotas, distribuidores, corporativos) |
| `company_contacts` | Contactos de empresas |
| `fleets` | Flotas vehiculares de empresas |
| `fleet_vehicles` | Vehículos específicos en flotas |
| `company_contracts` | Contratos empresariales |
| `company_price_rules` | Reglas de precios especiales por contrato |

## 🔌 API Endpoints

### Catálogo Público de Llantas

#### Listar Llantas

```http
GET /api/tires
```

**Query Parameters:**

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `brand_id` | integer | ❌ | Filtrar por ID de marca |
| `brand_slug` | string | ❌ | Filtrar por slug de marca |
| `width` | integer | ❌ | Ancho de llanta (100-400) |
| `profile` | integer | ❌ | Perfil/aspect ratio (20-90) |
| `rim` | integer | ❌ | Diámetro de rin (10-26) |
| `runflat` | boolean | ❌ | Solo llantas runflat |
| `all_terrain` | boolean | ❌ | Solo llantas all-terrain |
| `per_page` | integer | ❌ | Items por página (1-100) |

**Ejemplo:**

```bash
curl "http://localhost:8000/api/tires?brand_slug=michelin&width=205&profile=55&rim=16&per_page=20"
```

**Respuesta:**

```json
{
  "data": [
    {
      "id": 1,
      "sku": "MICH-PRIM-205-55-16",
      "name": "Michelin Primacy 4 205/55R16",
      "slug": "michelin-primacy-4-205-55-r16",
      "pattern": "Primacy 4",
      "usage": "passenger",
      "brand": {
        "id": 1,
        "name": "Michelin",
        "slug": "michelin"
      },
      "size": {
        "id": 1,
        "label": "205/55 R16.0",
        "width": 205,
        "aspect_ratio": 55,
        "rim_diameter": 16.0
      },
      "load_index": 91,
      "speed_rating": "V",
      "flags": {
        "is_runflat": false,
        "is_all_terrain": false,
        "is_highway": false,
        "is_winter": false,
        "is_summer": true
      },
      "pricing": {
        "base_price": 450000.00,
        "sale_price": null,
        "effective_price": 450000.00,
        "currency": "COP"
      }
    }
  ],
  "links": { /* ... */ },
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 15
  }
}
```

#### Ver Detalle de Llanta

```http
GET /api/tires/{id}
```

**Ejemplo:**

```bash
curl "http://localhost:8000/api/tires/1"
```

### Búsqueda de Llantas

#### Buscar por Vehículo

```http
GET /api/tires/search/by-vehicle
```

**Query Parameters:**

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `vehicle_id` | integer | ✅ | ID del vehículo |
| `brand_id` | integer | ❌ | Filtrar por marca de llanta |
| `min_price` | numeric | ❌ | Precio mínimo |
| `max_price` | numeric | ❌ | Precio máximo |
| `min_load_index` | integer | ❌ | Índice de carga mínimo |
| `min_speed_rating` | string | ❌ | Velocidad mínima (H, V, W, etc.) |
| `usage` | string | ❌ | Tipo de uso (passenger, suv, etc.) |
| `is_runflat` | boolean | ❌ | Solo llantas runflat |
| `is_all_terrain` | boolean | ❌ | Solo llantas all-terrain |
| `per_page` | integer | ❌ | Items por página (1-100) |

**Ejemplo:**

```bash
curl "http://localhost:8000/api/tires/search/by-vehicle?vehicle_id=1&min_price=300000&max_price=500000&per_page=10"
```

**Respuesta:**

```json
{
  "data": [
    {
      "id": 1,
      "sku": "MICH-PRIM-205-55-16",
      "name": "Michelin Primacy 4 205/55R16",
      "slug": "michelin-primacy-4-205-55-r16",
      "pattern": "Primacy 4",
      "usage": "passenger",
      "brand": {
        "id": 1,
        "name": "Michelin",
        "slug": "michelin"
      },
      "size": {
        "id": 1,
        "label": "205/55 R16.0",
        "width": 205,
        "aspect_ratio": 55,
        "rim_diameter": 16.0
      },
      "load_index": 91,
      "speed_rating": "V",
      "flags": {
        "is_runflat": false,
        "is_all_terrain": false,
        "is_highway": false,
        "is_winter": false,
        "is_summer": true
      },
      "pricing": {
        "base_price": "450000.00",
        "sale_price": null,
        "effective_price": "450000.00",
        "currency": "COP"
      }
    }
  ],
  "links": { "first": "...", "last": "...", "prev": null, "next": null },
  "meta": {
    "current_page": 1,
    "per_page": 10,
    "total": 2,
    "filters": {
      "brand_id": null,
      "min_price": 300000,
      "max_price": 500000
    },
    "vehicle": {
      "id": 1,
      "brand": "Chevrolet",
      "line": "Spark",
      "version": "GT 1.4L",
      "year": 2020
    }
  }
}
```

#### Buscar por Medida

```http
GET /api/tires/search/by-size
```

**Query Parameters:**

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `width` | integer | ✅ | Ancho (ej: 205) |
| `aspect_ratio` | integer | ✅ | Perfil (ej: 55) |
| `rim_diameter` | numeric | ✅ | Rin (ej: 16) |
| *(otros filtros)* | - | ❌ | Mismos filtros que búsqueda por vehículo |

**Ejemplo:**

```bash
curl "http://localhost:8000/api/tires/search/by-size?width=205&aspect_ratio=55&rim_diameter=16"
```

### Carrito de Compras

#### Ver Carrito

```http
GET /api/cart?cart_token={token}
```

**Headers:**
- `Authorization: Bearer {token}` (opcional, para usuarios autenticados)

**Respuesta:**

```json
{
  "data": {
    "id": 1,
    "token": "cart_abc123xyz",
    "status": "active",
    "items": [
      {
        "id": 1,
        "quantity": 4,
        "unit_price": "450000.00",
        "subtotal": "1800000.00",
        "buyable": {
          "id": 1,
          "name": "Michelin Primacy 4 205/55R16",
          "sku": "MICH-PRIM-205-55-16"
        }
      }
    ],
    "totals": {
      "subtotal": "1800000.00",
      "tax": "0.00",
      "total": "1800000.00",
      "currency": "COP"
    }
  },
  "meta": {
    "cart_token": "cart_abc123xyz"
  }
}
```

#### Agregar Item

```http
POST /api/cart/items
Content-Type: application/json
```

**Body:**

```json
{
  "cart_token": "cart_abc123xyz",
  "tire_id": 1,
  "quantity": 4
}
```

#### Actualizar Cantidad

```http
PUT /api/cart/items/{item_id}
Content-Type: application/json
```

```json
{
  "quantity": 2
}
```

#### Eliminar Item

```http
DELETE /api/cart/items/{item_id}
```

### Checkout y Órdenes

#### Crear Orden

```http
POST /api/checkout
Content-Type: application/json
```

**Body:**

```json
{
  "cart_token": "cart_abc123xyz",
  "customer": {
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "phone": "+573001234567",
    "document_type": "CC",
    "document_number": "1234567890"
  },
  "delivery": {
    "type": "home",
    "address": "Calle 10 # 20-30",
    "city": "Medellín",
    "state": "Antioquia",
    "postal_code": "050001",
    "notes": "Timbre de la derecha"
  }
}
```

**Respuesta:**

```json
{
  "data": {
    "id": 1,
    "order_number": "ORD-20251119-0001",
    "status": "pending",
    "customer": { /* ... */ },
    "items": [ /* ... */ ],
    "totals": {
      "subtotal": "1800000.00",
      "tax": "0.00",
      "total": "1800000.00",
      "currency": "COP"
    },
    "payment_url": null
  }
}
```

#### Crear Pago

```http
POST /api/orders/{order_id}/payments
Content-Type: application/json
```

**Body:**

```json
{
  "method": "CARD",
  "amount": 1800000
}
```

**Respuesta:**

```json
{
  "data": {
    "id": 1,
    "status": "pending",
    "method": "CARD",
    "amount": "1800000.00",
    "checkout_url": "https://checkout.wompi.co/l/xxxxx"
  }
}
```

### Puntos de Servicio

#### Listar Talleres

```http
GET /api/service-locations?municipality=medellin&service_slug=instalacion
```

**Query Parameters:**

| Parámetro | Tipo | Descripción |
|-----------|------|-------------|
| `municipality` | string | Municipio (medellin, bello, envigado, etc.) |
| `service_slug` | string | Tipo de servicio (instalacion, alineacion, balanceo) |
| `only_active` | boolean | Solo activos (default: true) |

#### Ver Detalle de Taller

```http
GET /api/service-locations/{id}
```

### Autenticación

#### Registro

```http
POST /api/auth/register
Content-Type: application/json
```

```json
{
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Login

```http
POST /api/auth/login
Content-Type: application/json
```

```json
{
  "email": "juan@example.com",
  "password": "password123"
}
```

**Respuesta:**

```json
{
  "user": {
    "id": 1,
    "name": "Juan Pérez",
    "email": "juan@example.com"
  },
  "token": "1|xxxxxxxxxxxxxxxxxxxxx"
}
```

#### Perfil

```http
GET /api/auth/me
Authorization: Bearer {token}
```

#### Logout

```http
POST /api/auth/logout
Authorization: Bearer {token}
```

## 📊 Modelos y Relaciones

### Tire (Llanta)

```php
// Relaciones
$tire->brand;           // BelongsTo: Brand
$tire->tireSize;        // BelongsTo: TireSize

// Scopes
Tire::active()->get();
Tire::forSize(205, 55, 16)->get();
Tire::forVehicle($vehicle)->get();
Tire::forBrand(1)->get();
Tire::filterPriceBetween(300000, 500000)->get();

// Atributos calculados
$tire->effective_price; // sale_price ?? base_price
```

### Vehicle (Vehículo)

```php
// Relaciones
$vehicle->brand;        // BelongsTo: VehicleBrand
$vehicle->line;         // BelongsTo: VehicleLine
$vehicle->version;      // BelongsTo: VehicleVersion
$vehicle->tireFitments; // HasMany: VehicleTireFitment

// Scopes
Vehicle::active()->get();
Vehicle::forBrandLineYear(1, 1, 2020)->first();
```

### Cart (Carrito)

```php
// Relaciones
$cart->user;            // BelongsTo: User (nullable)
$cart->items;           // HasMany: CartItem

// Métodos
$cart->calculateTotals();
$cart->clearItems();
$cart->markAsCompleted();
```

### Order (Orden)

```php
// Relaciones
$order->user;           // BelongsTo: User (nullable)
$order->items;          // HasMany: OrderItem
$order->payments;       // HasMany: Payment

// Métodos
$order->updateStatus(OrderStatus::CONFIRMED);
$order->getTotalAmount();
```

## 🛠️ Servicios

### TireSearchService

Búsqueda avanzada de llantas con filtros.

```php
use App\Services\Catalog\TireSearchService;

$service = new TireSearchService();

// Por vehículo
$results = $service->searchByVehicle(
    vehicle: $vehicle,
    filters: ['brand_id' => 1, 'min_price' => 300000],
    perPage: 20
);

// Por medida
$results = $service->searchBySize(
    width: 205,
    aspectRatio: 55,
    rimDiameter: 16,
    filters: ['is_runflat' => true]
);
```

### CartService

Gestión de carritos de compra.

```php
use App\Services\Cart\CartService;

$service = new CartService();

// Obtener o crear carrito
$cart = $service->getOrCreateCart($token, $user);

// Agregar llanta
$cart = $service->addTireToCart($cart, $tire, $quantity);

// Actualizar cantidad
$cart = $service->updateItemQuantity($item, $newQuantity);

// Remover item
$cart = $service->removeItem($item);
```

### CheckoutService

Procesamiento de órdenes.

```php
use App\Services\Checkout\CheckoutService;

$service = new CheckoutService();

// Crear orden desde carrito
$order = $service->createOrderFromCart(
    cart: $cart,
    customerData: $customerData,
    deliveryData: $deliveryData
);
```

### WompiService

Integración con pasarela de pagos.

```php
use App\Services\Payment\WompiService;

$service = new WompiService();

// Crear link de pago
$checkout = $service->createPaymentLink(
    payment: $payment,
    customer: $customerData
);

// Verificar transacción
$transaction = $service->getTransaction($transactionId);
```

## 🧪 Testing

```bash
# Ejecutar todos los tests
php artisan test

# Con coverage
php artisan test --coverage

# Tests específicos
php artisan test --filter TireSearchTest
```

## 🚢 Despliegue

### Laravel Cloud (Recomendado)

Laravel Cloud proporciona un entorno optimizado para aplicaciones Laravel con configuración automática.

#### 1. Conectar Repositorio

```bash
# Instalar Laravel Cloud CLI
composer global require laravel/cloud

# Autenticar
laravel cloud:auth

# Conectar repositorio
laravel cloud:init
```

#### 2. Configurar Variables de Entorno

En el dashboard de Laravel Cloud, configura las siguientes variables:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tires-ecommerce.laravel.cloud

# Base de datos (Laravel Cloud proporciona MySQL automáticamente)
DB_CONNECTION=mysql
DB_HOST=${LARAVEL_CLOUD_DB_HOST}
DB_PORT=${LARAVEL_CLOUD_DB_PORT}
DB_DATABASE=${LARAVEL_CLOUD_DB_DATABASE}
DB_USERNAME=${LARAVEL_CLOUD_DB_USERNAME}
DB_PASSWORD=${LARAVEL_CLOUD_DB_PASSWORD}

# Wompi Payment Gateway
WOMPI_PUBLIC_KEY=pub_prod_xxxxx
WOMPI_PRIVATE_KEY=prv_prod_xxxxx
WOMPI_EVENTS_SECRET=xxxxx
WOMPI_WEBHOOK_URL=${APP_URL}/api/payments/wompi/webhook

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@megallantas.com
MAIL_FROM_NAME="Megallantas"

# Queue (Laravel Cloud usa Redis automáticamente)
QUEUE_CONNECTION=redis
REDIS_HOST=${LARAVEL_CLOUD_REDIS_HOST}
REDIS_PASSWORD=${LARAVEL_CLOUD_REDIS_PASSWORD}
REDIS_PORT=${LARAVEL_CLOUD_REDIS_PORT}

# Session & Cache
SESSION_DRIVER=redis
CACHE_DRIVER=redis
```

#### 3. Configurar Deploy Hooks

Laravel Cloud ejecuta automáticamente las migraciones. Para seeders iniciales, usa:

```yaml
# .laravel-cloud.yml
deploy:
  - php artisan migrate --force
  - php artisan db:seed --class=TiresCatalogSeeder --force
  - php artisan optimize
```

#### 4. Configurar Dominio Personalizado

En el dashboard de Laravel Cloud:
1. Ve a **Settings → Domains**
2. Agrega tu dominio: `api.megallantas.com`
3. Configura los registros DNS según las instrucciones

#### 5. Desplegar

```bash
# Push a main para desplegar automáticamente
git push origin main

# O usar el CLI
laravel cloud:deploy
```

#### 6. Verificar Deployment

```bash
# Ver logs en tiempo real
laravel cloud:logs

# Verificar status
laravel cloud:status
```

### Características de Laravel Cloud

✅ **SSL Automático**: Certificados HTTPS gratuitos
✅ **Auto-scaling**: Escala según demanda
✅ **MySQL & Redis**: Incluidos y gestionados
✅ **Backups Automáticos**: Diarios de base de datos
✅ **Zero-downtime Deploys**: Sin interrupciones
✅ **Monitoring**: Métricas y alertas integradas
✅ **Queue Workers**: Gestionados automáticamente

### Despliegue Manual (VPS/Servidor Tradicional)

Si prefieres un servidor tradicional:

#### 1. Requisitos del Servidor

- Ubuntu 22.04 LTS
- PHP 8.2 + extensiones
- MySQL 8.0
- Redis
- Nginx
- Supervisor (para queues)

#### 2. Configurar Nginx

```nginx
server {
    listen 80;
    server_name api.megallantas.com;
    root /var/www/tires-ecommerce/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### 3. Configurar Queue Worker

```ini
# /etc/supervisor/conf.d/tires-ecommerce-worker.conf
[program:tires-ecommerce-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/tires-ecommerce/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/tires-ecommerce/storage/logs/worker.log
stopwaitsecs=3600
```

#### 4. Deploy Script

```bash
#!/bin/bash
cd /var/www/tires-ecommerce

# Modo mantenimiento
php artisan down

# Actualizar código
git pull origin main

# Instalar dependencias
composer install --no-dev --optimize-autoloader

# Optimizar
php artisan optimize
php artisan migrate --force

# Reiniciar servicios
php artisan queue:restart
sudo supervisorctl restart tires-ecommerce-worker:*

# Salir de mantenimiento
php artisan up
```

## 📄 Licencia

Este proyecto es propiedad de **Megallantas** y es de código privado.

## 👥 Equipo

Desarrollado por el equipo de Web Development Mega.

---

**¿Preguntas o problemas?** Abre un issue en el repositorio o contacta al equipo de desarrollo

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
