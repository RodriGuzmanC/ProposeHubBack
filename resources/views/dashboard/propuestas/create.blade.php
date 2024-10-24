<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Propuesta</title>
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
                        <a href="{{ route('dashboard') }}" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Clientes</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('propuestas.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Propuestas</p>
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
                        <h1 class="m-0">Crear Propuesta</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-tabs" id="propuestaTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="plantilla-tab" data-toggle="tab" href="#plantilla" role="tab" aria-controls="plantilla" aria-selected="true">Plantilla</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="contacto-tab" data-toggle="tab" href="#contacto" role="tab" aria-controls="contacto" aria-selected="false">Contacto</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="servicio-tab" data-toggle="tab" href="#servicio" role="tab" aria-controls="servicio" aria-selected="false">Servicios</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="informacion-tab" data-toggle="tab" href="#informacion" role="tab" aria-controls="informacion" aria-selected="false">Información</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('propuestas.store') }}" method="POST" id="propuestaForm">
                                    @csrf
                                    <div class="tab-content" id="propuestaTabContent">
                                        <div class="tab-pane fade show active" id="plantilla" role="tabpanel" aria-labelledby="plantilla-tab">
                                            <h3 class="mt-3">Selecciona una Plantilla</h3>
                                            <div class="row">
                                                @foreach($plantillas as $plantilla)
                                                    <div class="col-md-4">
                                                        <div class="card mb-3">
                                                            <div class="card-header">{{ $plantilla->nombre }}</div>
                                                            <div class="card-body">
                                                                <p>{{ $plantilla->descripcion }}</p>
                                                                <button type="button" class="btn btn-primary" onclick="selectTemplate('{{ $plantilla->id }}')">Usar {{ $plantilla->nombre }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button type="button" class="btn btn-primary" onclick="nextTab('contacto')">Siguiente</button>
                                        </div>
                                        
                                        <div class="tab-pane fade" id="contacto" role="tabpanel" aria-labelledby="contacto-tab">
                                            <h3 class="mt-3">Información de Contacto</h3>
                                            <div class="form-group">
                                                <label for="cliente">Seleccionar Cliente</label>
                                                <select class="form-control" id="cliente" name="cliente_id" required>
                                                    <option value="">Seleccione un cliente</option>
                                                    @foreach($clientes as $cliente)
                                                        <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="nombre_contacto">Nombre de Contacto</label>
                                                <input type="text" class="form-control" id="nombre_contacto" name="nombre_contacto" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="email_contacto">Email de Contacto</label>
                                                <input type="email" class="form-control" id="email_contacto" name="email_contacto">
                                            </div>
                                            
                                            <button type="button" class="btn btn-secondary" onclick="prevTab('plantilla')">Anterior</button>
                                            <button type="button" class="btn btn-primary" onclick="nextTab('servicio')">Siguiente</button>
                                        </div>
                                        
                                        <div class="tab-pane fade" id="servicio" role="tabpanel" aria-labelledby="servicio-tab">
                                            <h3 class="mt-3">Selecciona Servicios</h3>
                                            <div class="form-group">
                                                <label for="servicios">Servicios</label>
                                                <select multiple class="form-control" id="servicios" name="servicios[]">
                                                    <option value="1">Diseño Web</option>
                                                    <option value="2">Tienda Virtual</option>
                                                    <option value="3">Google Ads</option>
                                                    <option value="4">Aplicación Web</option>
                                                </select>
                                            </div>
                                            <button type="button" class="btn btn-secondary" onclick="prevTab('contacto')">Anterior</button>
                                            <button type="button" class="btn btn-primary" onclick="nextTab('informacion')">Siguiente</button>
                                        </div>
                                        <div class="tab-pane fade" id="informacion" role="tabpanel" aria-labelledby="informacion-tab">
                                            <h3 class="mt-3">Información Adicional</h3>
                                            <div class="form-group">
                                                <label for="informacion_adicional">Información Adicional</label>
                                                <textarea class="form-control" id="informacion_adicional" name="informacion_adicional"></textarea>
                                            </div>
                                            <button type="button" class="btn btn-secondary" onclick="prevTab('servicio')">Anterior</button>
                                            <button type="submit" class="btn btn-success">Crear Propuesta</button>
                                        </div>
                                    </div>
                                </form>
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
<script>
    function selectTemplate(templateId) {
        console.log("Plantilla seleccionada: " + templateId);
        nextTab('contacto'); // Cambia a la pestaña de contacto
    }

    function nextTab(tabId) {
        $('#propuestaTabs a[href="#' + tabId + '"]').tab('show');
    }

    function prevTab(tabId) {
        $('#propuestaTabs a[href="#' + tabId + '"]').tab('show');
    }
</script>
</body>
</html>
