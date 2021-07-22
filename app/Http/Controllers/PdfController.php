<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Convocatoria;
use Spatie\Browsershot\Browsershot;
use PDF;

class PdfController extends Controller
{
    /**
     * generateProjectSumary
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function generateProjectSumary(Convocatoria $convocatoria, Proyecto $proyecto, Request $request)
    {
        if($_COOKIE[config('session.cookie')]){
            $datos = null;
            if(!empty($proyecto->idi)){
                $datos = $proyecto->idi;
                $opcionesIDiDropdown = collect(json_decode(Storage::get('json/opciones-aplica-no-aplica.json'), true));
                $datos->relacionado_plan_tecnologico = $opcionesIDiDropdown->where('value', $datos->relacionado_plan_tecnologico)->first();
            }else if(!empty($proyecto->ta)){
                $datos = $proyecto->ta;
            }

            $base64Arbolproblemas = $this->takeScreenshot(route('convocatorias.proyectos.arbol-problemas', ['proyecto' => $proyecto->id, 'convocatoria' => $convocatoria->id]));
            $base64Arbolobjetivos = $this->takeScreenshot(route('convocatorias.proyectos.arbol-objetivos', ['proyecto' => $proyecto->id, 'convocatoria' => $convocatoria->id]));
            $base64GantProductos = $this->takeScreenshot(route('convocatorias.proyectos.productos.index', ['proyecto' => $proyecto->id, 'convocatoria' => $convocatoria->id]), '.flex.relative.mt-10');
            $base64GantActividades = $this->takeScreenshot(route('convocatorias.proyectos.actividades.index', ['proyecto' => $proyecto->id, 'convocatoria' => $convocatoria->id]), '.flex.relative.mt-10');
            $base64CadenaValor = $this->takeScreenshot(route('convocatorias.proyectos.cadena-valor', ['proyecto' => $proyecto->id, 'convocatoria' => $convocatoria->id]), '#orgchart_div');

            
            $pdf = PDF::loadView('Convocatorias.Proyectos.ResumenPdf', [
                'convocatoria' => $convocatoria, 
                'proyecto' => $proyecto,
                'datos' => $datos,
                'base64Arbolproblemas' => $base64Arbolproblemas,
                'base64Arbolobjetivos' => $base64Arbolobjetivos,
                'base64GantProductos' => $base64GantProductos,
                'base64GantActividades' => $base64GantActividades,
                'base64CadenaValor' => $base64CadenaValor,
                'proyectoAnexo' => $proyecto->proyectoAnexo()->select('proyecto_anexo.id', 'proyecto_anexo.anexo_id', 'proyecto_anexo.archivo', 'anexos.nombre')
                ->join('anexos', 'proyecto_anexo.anexo_id', 'anexos.id')->get(),
                'tiposImpacto'    => collect(json_decode(Storage::get('json/tipos-impacto.json'), true)),
                ]);
            return $pdf->stream('Proyecto '.$proyecto->id.' - SIPRO-SPA.pdf');

        }
        return redirect()->route('login');
    }

    private function takeScreenshot($route, $select = null)
    {
        $shot = Browsershot::url($route.'?to_pdf=1')
        ->useCookies([
            'XSRF-TOKEN' => csrf_token(),
            config('session.cookie') => $_COOKIE[config('session.cookie')],
            ])
        ->windowSize(1550, 800)
        ->deviceScaleFactor(2);
        if(!empty($select)){
            $shot->select($select);
        }else{
            $shot->fullPage();
        }
        return $shot->base64Screenshot();
    }
}
