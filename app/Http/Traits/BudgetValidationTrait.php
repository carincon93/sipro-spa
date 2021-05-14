<?php

namespace App\Http\Traits;

trait BudgetValidationTrait {

    /**
     * viaticsValidation
     *
     * @param  mixed $totalViatics
     * @param  mixed $value
     * @param  mixed $qtyItems
     * @param  mixed $projectSennovaBudgetValue
     * @param  mixed $projectSennovaBudgetQtyItems
     * @return void
     */
    public static function viaticsValidation($totalViatics, $value, $qtyItems, $projectSennovaBudgetValue, $projectSennovaBudgetQtyItems) {
        $total = 0;
        $total -= $projectSennovaBudgetValue * $projectSennovaBudgetQtyItems;
        $total += ($value * $qtyItems) + $totalViatics;
        return ($total > 4000000) ? true : false;
    }

    /**
     * specialConstructionServicesValidation
     *
     * @param  mixed $firstPriceQuote
     * @param  mixed $secondPriceQuote
     * @param  mixed $thirdPriceQuote
     * @param  mixed $project
     * @param  mixed $programmaticLineCode
     * @param  mixed $totalSpecialConstructionServices
     * @param  mixed $projectSennovaBudgetAverage
     * @param  mixed $percentageIndustrialMachinery
     * @return void
     */
    public static function specialConstructionServicesValidation($firstPriceQuote, $secondPriceQuote, $thirdPriceQuote, $budgetUsageCode, $totalSpecialConstructionServices, $projectSennovaBudgetAverage, $percentageIndustrialMachinery)
    {
        $firstPriceQuote  = $firstPriceQuote;
        $secondPriceQuote = $secondPriceQuote;
        $thirdPriceQuote  = $thirdPriceQuote ?? 0;

        $division = ($thirdPriceQuote > 0) ? 3 : 2;
        $average = ($firstPriceQuote + $secondPriceQuote + $thirdPriceQuote) / $division;

        // Calcula el promedio entre lo que viene del form + los estudios de mercado de todos los rubros de "SERVICIOS ESPECIALES DE CONSTRUCCIÓN"
        $averageAllBudgets = $average + ($totalSpecialConstructionServices - $projectSennovaBudgetAverage);

        // "El valor no debe superar el 5% del rubro de "MAQUINARIA INDUSTRIAL" y no lo debe dejar guardar hasta que se cumpla esa regla.
        // Dejar el siguiente mensaje para este rubro: Antes de diligenciar este rubro de "SERVICIOS ESPECIALES DE CONSTRUCCIÓN - ADECUACIONES Y CONSTRUCCIONES" tenga en cuenta que el valor total NO debe superar el 5% del total del rubro "MAQUINARIA INDUSTRIAL".
        if ($budgetUsageCode == '2020200500405') {
            return ($averageAllBudgets > $percentageIndustrialMachinery)  ? true : false;
        }

        return false;
    }

    public static function totalProjectBudgetValidation($totalProjectBudget, $percentage, $totalMachineryMaintenance, $firstPriceQuote, $secondPriceQuote, $thirdPriceQuote, $budgetUsageCode, $projectSennovaBudgetAverage)
    {
        $firstPriceQuote  = $firstPriceQuote;
        $secondPriceQuote = $secondPriceQuote;
        $thirdPriceQuote  = $thirdPriceQuote ?? 0;

        $division = ($thirdPriceQuote > 0) ? 3 : 2;
        $average = ($firstPriceQuote + $secondPriceQuote + $thirdPriceQuote) / $division;

        // Calcula el promedio entre lo que viene del form + los estudios de mercado de todos los rubros de "SERVICIOS ESPECIALES DE CONSTRUCCIÓN"
        $averageAllBudgets = $average + ($totalMachineryMaintenance - $projectSennovaBudgetAverage);

        // "El valor no debe superar el 5% del rubro de "MAQUINARIA INDUSTRIAL" y no lo debe dejar guardar hasta que se cumpla esa regla.
        // Dejar el siguiente mensaje para este rubro: Antes de diligenciar este rubro de "SERVICIOS ESPECIALES DE CONSTRUCCIÓN - ADECUACIONES Y CONSTRUCCIONES" tenga en cuenta que el valor total NO debe superar el 5% del total del rubro "MAQUINARIA INDUSTRIAL".
        if ($budgetUsageCode == '20202008007013' || $budgetUsageCode == '20202008007012' || $budgetUsageCode == '20202008007014' || $budgetUsageCode == '20202008007015' || $budgetUsageCode == '20202008007011') {
            return $averageAllBudgets > ($totalProjectBudget * $percentage) ? true : false;
        }

        return false;
    }
}
