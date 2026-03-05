# 🚗 VIP2CARS — Sistema de Gestión de Vehículos y Clientes

> Prueba técnica PHP · CRUD completo con Laravel + Módulo de Encuestas Anónimas

---

## 📋 Tabla de Contenidos

- [Descripción](#descripción)
- [Tecnologías](#tecnologías)
- [Requisitos del entorno](#requisitos-del-entorno)
- [Instalación y configuración](#instalación-y-configuración)
- [Puesta en marcha](#puesta-en-marcha)
- [Estructura de la base de datos](#estructura-de-la-base-de-datos)
- [Funcionalidades](#funcionalidades)
- [Estructura del proyecto](#estructura-del-proyecto)

---

## Descripción

Proyecto compuesto por dos módulos:

1. **Módulo Encuestas Anónimas** — modelo relacional diseñado para recolectar respuestas sin almacenar datos personales de los participantes.
2. **Módulo VIP2CARS (CRUD)** — gestión de vehículos y clientes del rubro automotriz, implementado en Laravel 11.

---

## Tecnologías

| Capa | Tecnología |
|---|---|
| Backend | PHP 8.2 + Laravel 12 |
| Base de datos | MySQL 8.0 / MariaDB 10.6 |
| Frontend | Blade + Bootstrap 5.3 |
| ORM | Eloquent |
| Validaciones | Laravel Form Requests |

---

## Requisitos del entorno

- **PHP** >= 8.2 con extensiones: `pdo`, `pdo_mysql`, `mbstring`, `openssl`, `xml`, `ctype`, `json`
- **Composer** >= 2.x
- **MySQL** >= 8.0 o **MariaDB** >= 10.6
- **Git**

---

## Instalación y configuración

```bash
# 1. Clonar el repositorio
git clone https://github.com/BryanP16/vip2cars.git
cd laravel_project

# 2. Instalar dependencias PHP
composer install

# 3. Copiar variables de entorno
cp .env.example .env # en linux
copy .env.example .env # en windows

# 4. Generar clave de aplicación
php artisan key:generate
```

### Configurar `.env`

Edita el archivo `.env` con tus credenciales de base de datos:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vip2cars
DB_USERNAME=root
DB_PASSWORD=
```

---

## Puesta en marcha

```bash
# 1. Crear la base de datos en MySQL
mysql -u root -p -e "CREATE DATABASE vip2cars CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. Ejecutar migraciones
php artisan migrate

# 3. Cargar datos de demostración (opcional)
php artisan db:seed

# 4. Levantar el servidor de desarrollo
php artisan serve
```

Abre tu navegador en **http://localhost:8000**

---

## Estructura de la base de datos

### Módulo 1 — Encuestas Anónimas

```
surveys          Encuestas disponibles
questions        Preguntas de cada encuesta (tipos: single, multiple, text, scale)
options          Opciones de respuesta para preguntas cerradas
survey_sessions  Sesiones anónimas (token + hashes, nunca datos personales)
responses        Respuestas individuales de cada sesión
```

**Diagrama ER** → ver `/er_diagram/diagrama_er.pdf`
**Script SQL** → ver `/database.sql`

### Módulo 2 — VIP2CARS

```
clients   Clientes del taller (nombre, apellido, documento, email, teléfono)
vehicles  Vehículos (placa, marca, modelo, año, color, VIN) → FK a clients
```

#### Campos completos de vehículos + clientes

| Campo | Tabla | Tipo |
|---|---|---|
| Placa | vehicles | VARCHAR(10) UNIQUE |
| Marca | vehicles | VARCHAR(80) |
| Modelo | vehicles | VARCHAR(80) |
| Año de fabricación | vehicles | YEAR |
| Color | vehicles | VARCHAR(40) |
| VIN | vehicles | VARCHAR(17) |
| Nombre del cliente | clients | VARCHAR(100) |
| Apellidos del cliente | clients | VARCHAR(100) |
| Nro. de documento | clients | VARCHAR(20) |
| Correo del cliente | clients | VARCHAR(150) UNIQUE |
| Teléfono del cliente | clients | VARCHAR(20) |

---

## Funcionalidades

### CRUD Vehículos
- ✅ **Crear** — formulario con cliente + vehículo en una sola pantalla
- ✅ **Listar** — tabla paginada (10 por página)
- ✅ **Buscar** — búsqueda full-text por placa, marca, modelo, nombre, documento
- ✅ **Ver detalle** — tarjeta con todos los datos del vehículo y cliente
- ✅ **Editar** — formulario pre-llenado con validaciones
- ✅ **Eliminar** — soft delete (los registros se conservan en la BD)

### Validaciones implementadas
- Placa única (excluyendo el propio registro en edición)
- Formato de placa con regex
- Email único por cliente con validación RFC+DNS
- Teléfono con regex de formato internacional
- Año entre 1900 y año actual + 1
- VIN de exactamente 17 caracteres alfanuméricos
- Mensajes de error en español

### Buenas prácticas
- **Form Requests** para validación desacoplada del controlador
- **Soft Deletes** en `clients` y `vehicles` (datos preservados)
- **Transacciones DB** (`DB::transaction`) en store/update
- **Query Scopes** `search()` en ambos modelos
- **Eager Loading** con `with('client')` para evitar N+1
- **Paginación** con `withQueryString()` para mantener filtros
- **Migraciones** versionadas y reproducibles
- **Seeders** idempotentes con `firstOrCreate`
- Código en **inglés**, comentarios y UI en **español**

---

## Estructura del proyecto

```
laravel_project/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── VehicleController.php   # CRUD completo
│   │   └── Requests/
│   │       ├── StoreVehicleRequest.php  # Validaciones crear
│   │       └── UpdateVehicleRequest.php # Validaciones editar
│   └── Models/
│       ├── Client.php                   # Modelo cliente + scopes
│       └── Vehicle.php                  # Modelo vehículo + scopes
├── database/
│   ├── migrations/
│   │   ├── ..._create_clients_table.php
│   │   └── ..._create_vehicles_table.php
│   └── seeders/
│       └── DatabaseSeeder.php           # Datos demo
├── resources/views/
│   ├── layouts/app.blade.php            # Layout base Bootstrap 5
│   └── vehicles/
│       ├── index.blade.php              # Listado + búsqueda
│       ├── create.blade.php             # Formulario crear
│       ├── edit.blade.php               # Formulario editar
│       └── show.blade.php               # Detalle
├── routes/web.php                       # Route::resource('vehicles')
└── .env.example
```

---

## Datos de demostración

Tras ejecutar `php artisan db:seed` se crean:

| Cliente | Placa | Marca / Modelo |
|---|---|---|
| Carlos Mendoza Ríos | ABC-123 | Toyota Corolla 2020 |
| Ana Torres Vega | DEF-789 | Hyundai Tucson 2021 |
| Luis García Paredes | GHI-012 | Ford Explorer 2019 |
| María López Castillo | JKL-345 | BMW 320i 2023 |
| Pedro Ruiz Flores | MNO-678 | Mercedes-Benz GLE 350 2022 |

---

*Desarrollado con Laravel 11 · PHP 8.2 · MySQL 8 · Bootstrap 5*
