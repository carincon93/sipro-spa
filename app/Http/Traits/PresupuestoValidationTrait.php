<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;

trait PresupuestoValidationTrait
{
    /**
     * totalUsoPresupuestal
     *
     * Obtiene el total de un grupo presupuestal dado el código del segundo grupo presupuestal
     * 
     * @param  mixed $proyecto
     * @param  mixed $codigo
     * @return int
     */
    public static function totalUsoPresupuestal($proyecto, $codigo)
    {
        $total = 0;

        foreach ($proyecto->proyectoPresupuesto as $proyectoPresupuesto) {
            $codigoSegundoPresupuestal = $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->segundoGrupoPresupuestal->codigo;
            if ($proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->sumar_al_presupuesto) {
                if ($codigoSegundoPresupuestal == $codigo) {
                    $total += $proyectoPresupuesto->getPromedioAttribute();
                }
            }
        }

        return $total;
    }

    /**
     * viaticosValidation
     *
     * REQ-LINEA-66 Para el proyecto solo se podrá destinar hasta $4.460.000 de viáticos, lo cual comprende la sumatoria de todos los rubros que tengan esta finalidad. 
     * 
     * @param  mixed $proyecto
     * @param  mixed $segundoGrupoPresupuestalCodigo
     * @param  mixed $valor
     * @param  mixed $numeroItems
     * @param  mixed $valorGuardado
     * @param  mixed $numeroItemsGuardado
     * @return void
     */
    public static function viaticosValidation($proyecto, $segundoGrupoPresupuestalCodigo, $valor, $numeroItems)
    {
        if ($segundoGrupoPresupuestalCodigo == '2042186' || $segundoGrupoPresupuestalCodigo == '2041101' || $segundoGrupoPresupuestalCodigo == '2041102') {
            if ($proyecto->tipoProyecto->lineaProgramatica->codigo != 66 || $proyecto->tipoProyecto->lineaProgramatica->codigo == 66 && $segundoGrupoPresupuestalCodigo == '2041101') {
                return false;
            }

            $total = 0;
            $total += ($valor * $numeroItems) + self::totalUsoPresupuestal($proyecto, '2041101');

            return ($total > 4460000) ? true : false;
        }

        return false;
    }

    /**
     * serviciosEspecialesConstruccionValidation
     *
     * REQ-LINEA-66 "El valor no debe superar el 5% del rubro de "MAQUINARIA INDUSTRIAL" y no lo debe dejar guardar hasta que se cumpla esa regla.
     * Dejar el siguiente mensaje para este rubro: Antes de diligenciar este rubro de "SERVICIOS ESPECIALES DE CONSTRUCCIÓN - ADECUACIONES Y CONSTRUCCIONES" tenga en cuenta que el valor total NO debe superar el 5% del total del rubro "MAQUINARIA INDUSTRIAL".
     * 
     * @param  mixed $proyecto
     * @param  mixed $proyectoPresupuesto
     * @param  mixed $metodo
     * @param  mixed $primerValor
     * @param  mixed $segundoValor
     * @param  mixed $tercerValor
     * @return boolean
     */
    public static function serviciosEspecialesConstruccionValidation($proyecto, $proyectoPresupuesto, $metodo, $primerValor, $segundoValor, $tercerValor)
    {
        $porcentajeMaquinariaIndustrial         = self::totalUsoPresupuestal($proyecto, '2020200500405') * 0.05;
        $totalServiciosEspecialesConstruccion   = self::totalUsoPresupuestal($proyecto, '2020200500405');

        if ($porcentajeMaquinariaIndustrial == 0) {
            return false;
        }

        $tercerValor = $tercerValor ?? 0;

        $division = ($tercerValor > 0) ? 3 : 2;
        $promedio = ($primerValor + $segundoValor + $tercerValor) / $division;

        $promedioPresupuestoGuardado = $metodo == 'store' ? 0 : $proyectoPresupuesto->getPromedioAttribute();

        // Calcula el promedio entre lo que viene del form + los estudios de mercado de todos los rubros de "SERVICIOS ESPECIALES DE CONSTRUCCIÓN"
        $promedioPresupuestoTotal = $promedio + ($totalServiciosEspecialesConstruccion - $promedioPresupuestoGuardado);

        if ($proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->codigo == '2020200500405') {
            return ($promedioPresupuestoTotal > $porcentajeMaquinariaIndustrial)  ? true : false;
        }

        return false;
    }

    /**
     * serviciosMantenimientoValidation
     *
     * REQ-LINEA-23 Antes de diligenciar este rubro en el aplicativo SGPS – SIPRO de tenga en cuenta que NO debe superar el 5% del valor total del proyecto, sin incluir el valor de los contratos de aprendizaje.
     * 
     * @param  mixed $proyecto
     * @param  mixed $proyectoPresupuesto
     * @param  mixed $metodo
     * @param  mixed $primerValor
     * @param  mixed $segundoValor
     * @param  mixed $tercerValor
     * @return boolean
     */
    public static function serviciosMantenimientoValidation($proyecto, $proyectoPresupuesto, $metodo, $primerValor, $segundoValor, $tercerValor)
    {
        if ($proyecto->getTotalProyectoPresupuestoAttribute() == 0) {
            return false;
        }

        $tercerValor  = $tercerValor ?? 0;

        $division = ($tercerValor > 0) ? 3 : 2;
        $promedio = ($primerValor + $segundoValor + $tercerValor) / $division;

        $codigoUsoPresupuestal = $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->segundoGrupoPresupuestal->codigo;
        $promedioPresupuestoGuardado = $metodo == 'store' ? 0 : $proyectoPresupuesto->getPromedioAttribute();

        $totalMantenimientoMaquinaria = self::totalUsoPresupuestal($proyecto, '020202008');

        $promedioPresupuestoTotal = $promedio + ($totalMantenimientoMaquinaria - $promedioPresupuestoGuardado);

        if ($codigoUsoPresupuestal == '020202008') {
            return $promedioPresupuestoTotal > ($proyecto->getTotalProyectoPresupuestoAttribute() * 0.05) ? true : false;
        }

        return false;
    }

    /**
     * adecuacionesYContruccionesValidation
     *
     * REQ-LINEA-23 Antes de diligenciar la información en el aplicativo SGPS - SIPRO tenga en cuenta que el total a formular NO debe superar el valor de 100 salarios mínimos (debe ser menor o igual a $90.852.600).
     * 
     * @param  mixed $proyecto
     * @param  mixed $proyectoPresupuesto
     * @param  mixed $metodo
     * @param  mixed $primerValor
     * @param  mixed $segundoValor
     * @param  mixed $tercerValor
     * @return void
     */
    public static function adecuacionesYContruccionesValidation($proyecto,  $proyectoPresupuesto, $metodo, $primerValor, $segundoValor, $tercerValor)
    {
        $promedio = 0;

        $salarioMinimo = json_decode(Storage::get('json/salario-minimo.json'), true);

        $codigoUsoPresupuestal = $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->codigo;

        if ($codigoUsoPresupuestal == '2020200500406' || $codigoUsoPresupuestal == '2020200500407' || $codigoUsoPresupuestal == '2020200500405' || $codigoUsoPresupuestal == '20202005004023') {
            $tercerValor = $tercerValor ?? 0;

            $division = ($tercerValor > 0) ? 3 : 2;
            $promedio = ($primerValor + $segundoValor + $tercerValor) / $division;

            if ($promedio > ($salarioMinimo['value'] * 100)) {
                return true;
            }
        }

        return false;
    }
}
