<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'VIP2CARS') — Sistema de Gestión</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <style>
        :root { --vip-dark: #0f172a; --vip-gold: #f59e0b; }
        body { background: #f8fafc; font-family: 'Segoe UI', system-ui, sans-serif; }
        .navbar-brand span { color: var(--vip-gold); }
        .navbar { background: var(--vip-dark) !important; }
        .btn-vip { background: var(--vip-gold); color: var(--vip-dark); font-weight: 600; border: none; }
        .btn-vip:hover { background: #d97706; color: #fff; }
        .card { border: none; box-shadow: 0 1px 8px rgba(0,0,0,.08); border-radius: 12px; }
        .badge-plate { background: var(--vip-dark); color: var(--vip-gold); font-family: monospace; font-size: 1rem; padding: .35em .75em; border-radius: 6px; letter-spacing: .08em; }
        table thead { background: var(--vip-dark); color: #fff; }
        .form-label { font-weight: 600; font-size: .875rem; color: #475569; }
        .section-title { font-size: .7rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: var(--vip-gold); margin-bottom: .5rem; }
    </style>
</head>
<body>
<nav class="navbar navbar-dark px-4 py-3">
    <a class="navbar-brand fw-bold fs-5" href="{{ route('vehiculos.index') }}">
        🚗 VIP<span>2CARS</span>
    </a>
    <a href="{{ route('vehiculos.create') }}" class="btn btn-vip btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Nuevo Registro
    </a>
</nav>

<main class="container py-4">
    @foreach (['success','error','warning'] as $type)
        @if(session($type))
            <div class="alert alert-{{ $type === 'success' ? 'success' : ($type === 'error' ? 'danger' : 'warning') }} alert-dismissible fade show" role="alert">
                {{ session($type) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
