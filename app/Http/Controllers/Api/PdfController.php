<?php

namespace App\Http\Controllers\API;

use DOMDocument;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Knp\Snappy\Pdf;
use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Models\Propuesta;

class PdfController extends Controller
{
    protected $pdf;

    public function __construct(Pdf $pdf)
    {
        $this->pdf = $pdf;
    }

    public function generatePdf(Request $request)
    {
        try {
            // Recibe el HTML como string desde la solicitud
            $id = $request->input('id');  // id de la propuesta           
            $propuesta = Propuesta::with(['estado', 'plantilla', 'servicio', 'usuario'])->find($id);

            if (!$propuesta) {
                //return response()->json(['mensaje' => 'Propuesta no encontrada'], 404);
                throw new \Exception('Propuesta no encontrada', 404);
            }

            $htmlContenido = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Propuesta</title>
                <style>'.$propuesta->css.'</style>
                <style>.page-container{display: contents !important; padding-top: 0 !important; padding-bottom: 0 !important;}</style>
            </head>
            <body>
                <div>
                '.$propuesta->html.'
                <div>
            </body>
            </html>';
            $htm = $this->processImages($htmlContenido);
            $sinVideo = $this->processIframes($htm);
            
            /*return response()->json([
                "html" => $htmlContenido,
            ]);*/
            // Genera el PDF utilizando Snappy, sin necesidad de especificar la ruta del binario
            $pdf = SnappyPdf::loadHTML($sinVideo)
                ->setOption('page-width', '164mm')
                ->setOption('page-height', '232.50mm')
                ->setOption('margin-top', '0mm')
                ->setOption('margin-bottom', '0mm')
                ->setOption('margin-left', '0mm')
                ->setOption('margin-right', '0mm');

            // Devuelve el PDF como respuesta
            return response($pdf->output())
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="archivo.pdf"');
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => mb_convert_encoding($e->getMessage(), 'UTF-8', 'UTF-8'),
                'error' => $e->getCode() === 404 ? 'No encontrados' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }


    public function processImages($htmlContent)
    {
        // Crear el crawler a partir del contenido HTML
        $crawler = new Crawler($htmlContent);

        // Buscar todas las etiquetas <img>
        $crawler->filter('img')->each(function (Crawler $node) {
            // Obtener el atributo src de la imagen
            $src = $node->attr('src');

            // Extraer el nombre de la imagen
            $imageName = basename($src);

            // Verificar si la imagen existe en el almacenamiento
            $imagePath = storage_path('app/public/images/' . $imageName);
            if (file_exists($imagePath)) {
                // Si la imagen existe, actualizar el src
                $domNode = $node->getNode(0); // Obtener el nodo DOM real
                if ($domNode instanceof \DOMElement) {
                    // Modificar el atributo 'src' del nodo real
                    $domNode->setAttribute('src', $imagePath);
                }
            } else {
                return '';
            }
        });

        // Devolver el HTML modificado
        return $crawler->html();
    }


    public function processIframes($htmlContent)
{
    // Crear el crawler a partir del contenido HTML
    $crawler = new Crawler($htmlContent);
    
    // Buscar todos los elementos <iframe>
    $crawler->filter('iframe')->each(function (Crawler $node) {
        // Crear un nuevo div para reemplazar el iframe
        $div = $node->getNode(0)->ownerDocument->createElement('div');
        
        // Mantener el id del iframe en el nuevo div (si tiene un id)
        if ($node->attr('id')) {
            $div->setAttribute('id', $node->attr('id'));
        }
        
        // Añadir el fondo negro al div
        $div->setAttribute('style', 'background-color: black;');
        
        // Reemplazar el iframe con el div
        $node->getNode(0)->parentNode->replaceChild($div, $node->getNode(0));
    });
    
    // Devolver el HTML modificado
    return $crawler->html();
}


}
