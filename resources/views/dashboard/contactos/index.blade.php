<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="#" class="brand-link">
            <span class="brand-text font-weight-light">Propuestas IA</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('contactos.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Contactos</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('propuestas.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Propuestas</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('plantillas.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-file"></i>
                            <p>Plantillas</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Cerrar sesión</p>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Contactos</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <!-- Pestañas -->
                <ul class="nav nav-tabs" id="contactTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="organizaciones-tab" data-toggle="tab" href="#organizaciones" role="tab" aria-controls="organizaciones" aria-selected="true">Organizaciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="clientes-tab" data-toggle="tab" href="#clientes" role="tab" aria-controls="clientes" aria-selected="false">Clientes</a>
                    </li>
                </ul>

                <!-- Contenido de las pestañas -->
                <div class="tab-content" id="contactTabsContent">
                    <div class="tab-pane fade show active" id="organizaciones" role="tabpanel" aria-labelledby="organizaciones-tab">
                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">Lista de Organizaciones</h3>
                            </div>
                            <div class="card-body">
                                <a href="{{ route('organizaciones.create') }}" class="btn btn-primary mb-2">Crear Nueva Organización</a>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Teléfono</th>
                                            <th>Correo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($organizaciones as $organizacion)
                                            <tr>
                                                <td>{{ $organizacion->id }}</td>
                                                <td>{{ $organizacion->nombre }}</td>
                                                <td>{{ $organizacion->telefono }}</td>
                                                <td>{{ $organizacion->correo }}</td>
                                                <td>
                                                    <a href="{{ route('organizaciones.edit', $organizacion->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                                    <form action="{{ route('organizaciones.destroy', $organizacion->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="clientes" role="tabpanel" aria-labelledby="clientes-tab">
                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">Lista de Clientes</h3>
                            </div>
                            <div class="card-body">
                                <a href="{{ route('clientes.create') }}" class="btn btn-primary mb-2">Crear Nuevo Cliente</a>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Correo</th>
                                            <th>Teléfono</th>
                                            <th>Organización</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($clientes as $cliente)
                                            <tr>
                                                <td>{{ $cliente->id }}</td>
                                                <td>{{ $cliente->nombre }}</td>
                                                <td>{{ $cliente->correo }}</td>
                                                <td>{{ $cliente->telefono }}</td>
                                                <td>{{ optional($cliente->organizacion)->nombre }}</td>
                                                <td>
                                                    <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                                    <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2023 <a href="#">Tu Empresa</a>.</strong>
        Todos los derechos reservados.
        <div class="float-right d-none d-sm-inline-block">
            <b>Versión</b> 1.0.0
        </div>
    </footer>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
</body>
</html>
