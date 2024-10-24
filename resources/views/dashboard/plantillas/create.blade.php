<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Plantilla</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <style>
        .editor-container {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }
        #code-view {
            width: 100%;
            border: 1px solid #ccc;
            padding: 10px;
            height: 200px;
            overflow-y: auto;
            background-color: #f9f9f9;
            resize: none;
            font-family: monospace;
        }
    </style>
    <script src="https://cdn.tiny.cloud/1/mah9o59oympet9qpv9gwa0ll7k39yoyc83w95obyqguaue3c/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        tinymce.init({
            selector: '#editor',
            plugins: 'lists link image table code',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
            setup: function (editor) {
                editor.on('change', function () {
                    updateCodeView();
                });
            }
        });

        function updateCodeView() {
            const html = tinymce.get('editor').getContent();
            document.getElementById('code-view').value = html;
        }

        function updateEditor() {
            const html = document.getElementById('code-view').value;
            tinymce.get('editor').setContent(html);
        }

        function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const htmlContent = tinymce.get('editor').getContent();
            doc.fromHTML(htmlContent, 10, 10);
            doc.save('plantilla.pdf');
        }

        document.addEventListener('DOMContentLoaded', function () {
            updateCodeView();
            document.getElementById('export-pdf').addEventListener('click', function () {
                exportToPDF();
            });
        });
    </script>
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
                        <a href="{{ route('plantillas.index') }}" class="nav-link active">
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
                <h1>Crear Nueva Plantilla</h1>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <form action="{{ route('plantillas.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nombre:</label>
                        <input type="text" name="name" class="form-control" required placeholder="Ingrese el nombre de la plantilla">
                    </div>
                    <div class="form-group">
                        <label for="category">Categoría:</label>
                        <input type="text" name="category" class="form-control" required placeholder="Ingrese la categoría">
                    </div>
                    <div class="form-group">
                        <label for="description">Descripción:</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Ingrese una descripción"></textarea>
                    </div>
                    <div class="editor-container">
                        <label for="content">Contenido:</label>
                        <textarea id="editor"></textarea>
                        <textarea name="content" id="code-view" oninput="updateEditor()" placeholder="Escribe HTML aquí..." style="display:none;"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active">
                            <label class="custom-control-label" for="is_active">Activa</label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" id="export-pdf" class="btn btn-success">
                            <i class="fas fa-file-pdf"></i> Exportar a PDF
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <a href="{{ route('plantillas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
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

<!-- Scripts para Bootstrap (jQuery y Bootstrap JS) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
