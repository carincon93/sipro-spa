<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;

trait PresupuestoValidationTrait
{
    public static function viaticosValidation($proyecto, $segundoGrupoPresupuestalCodigo, $valor, $numeroItems, $valorGuardado, $numeroItemsGuardado)
    {
        if ($segundoGrupoPresupuestalCodigo == '2042186' || $segundoGrupoPresupuestalCodigo == '2041101' || $segundoGrupoPresupuestalCodigo == '2041102') {
            if ($proyecto->tipoProyecto->lineaProgramatica->codigo != 66 || $proyecto->tipoProyecto->lineaProgramatica->codigo == 66 && $segundoGrupoPresupuestalCodigo == '2041101') {
                return false;
            }

            $total = 0;
            $total += ($valor * $numeroItems) + self::totalViaticosInteriorGastosAlumnos($proyecto);

            return ($total > 4000000) ? true : false;
        }

        return false;
    }

    public static function serviciosEspecialesConstruccionValidation($proyecto, $proyectoPresupuesto, $metodo, $primerValor, $segundoValor, $tercerValor)
    {
        $porcentajeMaquinariaIndustrial         = self::porcentajeMaquinariaIndustrial($proyecto);
        $totalServiciosEspecialesConstruccion   = self::totalServiciosEspecialesConstruccion($proyecto);

        if ($porcentajeMaquinariaIndustrial == 0) {
            return false;
        }

        $tercerValor = $tercerValor ?? 0;

        $division = ($tercerValor > 0) ? 3 : 2;
        $promedio = ($primerValor + $segundoValor + $tercerValor) / $division;

        $promedioPresupuestoGuardado = $metodo == 'store' ? 0 : $proyectoPresupuesto->getPromedioAttribute();

        // Calcula el promedio entre lo que viene del form + los estudios de mercado de todos los rubros de "SERVICIOS ESPECIALES DE CONSTRUCCIÓN"
        $promedioPresupuestoTotal = $promedio + ($totalServiciosEspecialesConstruccion - $promedioPresupuestoGuardado);

        // "El valor no debe superar el 5% del rubro de "MAQUINARIA INDUSTRIAL" y no lo debe dejar guardar hasta que se cumpla esa regla.
        // Dejar el siguiente mensaje para este rubro: Antes de diligenciar este rubro de "SERVICIOS ESPECIALES DE CONSTRUCCIÓN - ADECUACIONES Y CONSTRUCCIONES" tenga en cuenta que el valor total NO debe superar el 5% del total del rubro "MAQUINARIA INDUSTRIAL".
        if ($proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->codigo == '2020200500405') {
            return ($promedioPresupuestoTotal > $porcentajeMaquinariaIndustrial)  ? true : false;
        }

        return false;
    }

    public static function porcentajeMaquinariaIndustrial($proyecto)
    {
        return self::totalMaquinariaIndustrial($proyecto) * 0.05;
    }

    public static function serviciosMantenimientoValidation($proyecto, $proyectoPresupuesto, $metodo, $primerValor, $segundoValor, $tercerValor)
    {
        if ($proyecto->getTotalProyectoPresupuestoAttribute() == 0) {
            return false;
        }

        $tercerValor  = $tercerValor ?? 0;

        $division = ($tercerValor > 0) ? 3 : 2;
        $promedio = ($primerValor + $segundoValor + $tercerValor) / $division;

        $codigoUsoPresupuestal = $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->codigo;
        $promedioPresupuestoGuardado = $metodo == 'store' ? 0 : $proyectoPresupuesto->getPromedioAttribute();

        $totalMantenimientoMaquinaria = self::totalMantenimientoMaquinariaIdi($proyecto);

        $promedioPresupuestoTotal = $promedio + ($totalMantenimientoMaquinaria - $promedioPresupuestoGuardado);

        // El valor no debe superar el 5% del total del proyecto y no lo debe dejar finalizar este modulo hasta que se cumpla esa regla. 
        // Dejar el siguiente mensaje para este rubro: Antes de diligenciar este rubro de "MANTENIMIENTO DE MAQUINARIA, EQUIPO, TRANSPORTE Y SOFWARE" tenga en cuenta que NO debe superar el 5% del total del proyecto. 
        if ($codigoUsoPresupuestal == '20202008007013' || $codigoUsoPresupuestal == '20202008007012' || $codigoUsoPresupuestal == '20202008007014' || $codigoUsoPresupuestal == '20202008007015' || $codigoUsoPresupuestal == '20202008007011') {
            return $promedioPresupuestoTotal > ($proyecto->getTotalProyectoPresupuestoAttribute() * 0.05) ? true : false;
        }

        return false;
    }

    public static function totalMantenimientoMaquinariaIdi($proyecto)
    {
        $total = 0;

        foreach ($proyecto->proyectoPresupuesto as $proyectoPresupuesto) {
            $codigoUsoPresupuestal = $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->codigo;
            if ($proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->sumar_al_presupuesto) {
                if ($codigoUsoPresupuestal == '20202008007013' || $codigoUsoPresupuestal == '20202008007012' || $codigoUsoPresupuestal == '20202008007014' || $codigoUsoPresupuestal == '20202008007015' || $codigoUsoPresupuestal == '20202008007011') {
                    $total += $proyectoPresupuesto->getPromedioAttribute();
                }
            }
        }

        return $total;
    }

    public static function adecuacionesYContruccionesValidation($proyecto,  $proyectoPresupuesto, $metodo, $primerValor, $segundoValor, $tercerValor)
    {
        $promedio = 0;

        $salarioMinimoDiario = json_decode(Storage::get('json/salario-minimo-diario.json'), true);

        $codigoUsoPresupuestal = $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->codigo;

        if ($codigoUsoPresupuestal == '2020200500406' || $codigoUsoPresupuestal == '2020200500407' || $codigoUsoPresupuestal == '2020200500405' || $codigoUsoPresupuestal == '20202005004023') {
            $tercerValor = $tercerValor ?? 0;

            $division = ($tercerValor > 0) ? 3 : 2;
            $promedio = ($primerValor + $segundoValor + $tercerValor) / $division;

            if ($promedio > ($salarioMinimoDiario['value'] * 100)) {
                return true;
            }
        }

        return false;
    }

    /**
     * totalViaticosInteriorGastosAlumnos
     *
     * @param  mixed $proyecto
     * @return int
     */
    public static function totalViaticosInteriorGastosAlumnos($proyecto)
    {
        $total = 0;

        foreach ($proyecto->proyectoPresupuesto as $proyectoPresupuesto) {
            $presupuestoSennova = $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova;
            if ($presupuestoSennova->segundoGrupoPresupuestal->codigo != '2041101') {
                $codigoUsoPresupuestal = $presupuestoSennova->usoPresupuestal->codigo;
                if ($codigoUsoPresupuestal == '2020200600301' || $codigoUsoPresupuestal == '2020200600303' || $codigoUsoPresupuestal == '20202006004') {
                    $total += $proyectoPresupuesto->getPromedioAttribute();
                }
            }
        }

        return $total;
    }

    public static function totalMaquinariaIndustrial($proyecto)
    {
        $total = 0;

        foreach ($proyecto->proyectoPresupuesto as $proyectoPresupuesto) {

            if ($proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->sumar_al_presupuesto && $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->segundoGrupoPresupuestal->codigo == '2040115') {
                $total += $proyectoPresupuesto->getPromedioAttribute();
            }
        }

        return $total;
    }

    public static function totalServiciosEspecialesConstruccion($proyecto)
    {
        $total = 0;

        foreach ($proyecto->proyectoPresupuesto as $proyectoPresupuesto) {

            if ($proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->sumar_al_presupuesto && $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->codigo == '2020200500405') {
                $total += $proyectoPresupuesto->getPromedioAttribute();
            }
        }

        return $total;
    }

    public static function totalUsoPresupuestal($proyecto, $codigo)
    {
        $total = 0;

        foreach ($proyecto->proyectoPresupuesto as $proyectoPresupuesto) {
            $codigoUsoPresupuestal = $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->segundoGrupoPresupuestal->codigo;
            if ($proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->sumar_al_presupuesto) {
                if ($codigoUsoPresupuestal == $codigo) {
                    $total += $proyectoPresupuesto->getPromedioAttribute();
                }
            }
        }

        return $total;
    }
}
