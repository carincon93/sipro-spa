<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Convocatoria;
use App\Models\TipoProyectoSt;
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
    static function generateProjectSumary(Convocatoria $convocatoria, Proyecto $proyecto, $save = false)
    {
        if($_COOKIE[config('session.cookie')]){
            $datos = null;
            $tipoProyectoSt = null;
            if(!empty($proyecto->idi)){
                $datos = $proyecto->idi;
                $opcionesIDiDropdown = collect(json_decode(Storage::get('json/opciones-aplica-no-aplica.json'), true));
                $datos->relacionado_plan_tecnologico = $opcionesIDiDropdown->where('value', $datos->relacionado_plan_tecnologico)->first();
            }else if(!empty($proyecto->ta)){
                $datos = $proyecto->ta;
            }else if(!empty($proyecto->tp)){
                $datos = $proyecto->tp;
            }else if(!empty($proyecto->culturaInnovacion)){
                $datos = $proyecto->culturaInnovacion;
            }else if(!empty($proyecto->servicioTecnologico)){
                $datos = $proyecto->servicioTecnologico;
                if (auth()->user()->hasRole(13)) {
                $tipoProyectoSt = TipoProyectoSt::selectRaw("tipos_proyecto_st.id as value, CASE tipos_proyecto_st.tipo_proyecto
                    WHEN '1' THEN   concat(centros_formacion.nombre, chr(10), '∙ Tipo de proyecto: A', chr(10), '∙ Mesa técnica: ', mesas_tecnicas.nombre)
                    WHEN '2' THEN   concat(centros_formacion.nombre, chr(10), '∙ Tipo de proyecto: B', chr(10), '∙ Mesa técnica: ', mesas_tecnicas.nombre)
                END as label")->join('centros_formacion', 'tipos_proyecto_st.centro_formacion_id', 'centros_formacion.id')->join('mesas_tecnicas', 'tipos_proyecto_st.mesa_tecnica_id', 'mesas_tecnicas.id')->where('centros_formacion.regional_id', auth()->user()->centroFormacion->regional_id)->get();
                } else {
                    $tipoProyectoSt = TipoProyectoSt::selectRaw("tipos_proyecto_st.id as value, CASE tipos_proyecto_st.tipo_proyecto
                        WHEN '1' THEN   concat(centros_formacion.nombre, chr(10), '∙ Tipo de proyecto: A', chr(10), '∙ Mesa técnica: ', mesas_tecnicas.nombre)
                        WHEN '2' THEN   concat(centros_formacion.nombre, chr(10), '∙ Tipo de proyecto: B', chr(10), '∙ Mesa técnica: ', mesas_tecnicas.nombre)
                    END as label")->join('centros_formacion', 'tipos_proyecto_st.centro_formacion_id', 'centros_formacion.id')->join('mesas_tecnicas', 'tipos_proyecto_st.mesa_tecnica_id', 'mesas_tecnicas.id')->get();
                }
            }

            $base64Arbolproblemas = PdfController::takeScreenshot(route('convocatorias.proyectos.arbol-problemas', ['proyecto' => $proyecto->id, 'convocatoria' => $convocatoria->id]));
            $base64Arbolobjetivos = PdfController::takeScreenshot(route('convocatorias.proyectos.arbol-objetivos', ['proyecto' => $proyecto->id, 'convocatoria' => $convocatoria->id]));
            $base64GantProductos = PdfController::takeScreenshot(route('convocatorias.proyectos.productos.index', ['proyecto' => $proyecto->id, 'convocatoria' => $convocatoria->id]), '.flex.relative.mt-10');
            $base64GantActividades = PdfController::takeScreenshot(route('convocatorias.proyectos.actividades.index', ['proyecto' => $proyecto->id, 'convocatoria' => $convocatoria->id]), '.flex.relative.mt-10');
            $base64CadenaValor = PdfController::takeScreenshot(route('convocatorias.proyectos.cadena-valor', ['proyecto' => $proyecto->id, 'convocatoria' => $convocatoria->id]), '#orgchart_div');

            
            $pdf = PDF::loadView('Convocatorias.Proyectos.ResumenPdf', [
                'convocatoria' => $convocatoria, 
                'proyecto' => $proyecto,
                'datos' => $datos,
                'tipoProyectoSt' => $tipoProyectoSt,
                'base64Arbolproblemas' => $base64Arbolproblemas,
                'base64Arbolobjetivos' => $base64Arbolobjetivos,
                'base64GantProductos' => $base64GantProductos,
                'base64GantActividades' => $base64GantActividades,
                'base64CadenaValor' => $base64CadenaValor,
                'proyectoAnexo' => $proyecto->proyectoAnexo()->select('proyecto_anexo.id', 'proyecto_anexo.anexo_id', 'proyecto_anexo.archivo', 'anexos.nombre')
                ->join('anexos', 'proyecto_anexo.anexo_id', 'anexos.id')->get(),
                'tiposImpacto'    => collect(json_decode(Storage::get('json/tipos-impacto.json'), true)),
                'estadosInventarioEquipos'  => collect(json_decode(Storage::get('json/estados-inventario-equipos.json'), true)),
                ]);
            if ($save==false) {
                return $pdf->stream('Proyecto '.$proyecto->id.' - SIPRO-SPA.pdf');
            }else{
                $pdf->setWarnings(false)->save($convocatoria->id.'/'.$proyecto->id.'/Proyecto '.$proyecto->id.' - SIPRO-SPA.pdf');
            }

        }
        return redirect()->route('login');
    }

    static function takeScreenshot($route, $select = null)
    {
        $shot = Browsershot::url($route.'?to_pdf=1')
        ->useCookies([
            'XSRF-TOKEN' => csrf_token(),
            config('session.cookie') => $_COOKIE[config('session.cookie')],
            ])
        ->windowSize(1550, 800)
        ->deviceScaleFactor(2)
        ->addChromiumArguments([
            'no-sandbox',
            'disable-setuid-sandbox',
            'disable-background-timer-throttling',
            'disable-backgrounding-occluded-windows',
            'disable-renderer-backgrounding'
        ]);
        if(!empty($select)){
            $shot->select($select);
        }else{
            $shot->fullPage();
        }
        return $shot->base64Screenshot();
    }
}
