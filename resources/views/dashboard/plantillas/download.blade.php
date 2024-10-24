<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $plantilla->nombre }}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body>
    <div id="content">
        <h1>{{ $plantilla->nombre }}</h1>
        {!! $plantilla->contenido !!}
    </div>
    <button id="download-pdf">Descargar PDF</button>

    <script>
        document.getElementById('download-pdf').addEventListener('click', function() {
            const element = document.getElementById('content');
            html2pdf()
                .from(element)
                .save(`plantilla_{{ $plantilla->id }}.pdf`);
        });
    </script>
</body>
</html>
