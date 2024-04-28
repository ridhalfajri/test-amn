<?php

namespace App\Http\Controllers;

use App\Models\BillOfMaterial;
use App\Models\BillOfMaterialDetail;
use App\Models\MaterialRequirement;
use App\Models\MaterialRequirementDetail;
use App\Models\ProductionPlanning;
use App\Models\ProductionPlanningDetail;
use Illuminate\Http\Request;

class MaterialRequirementController extends Controller
{
    public function index()
    {
        $production_planning = ProductionPlanning::all();
        // loop production planning
        foreach ($production_planning as $product_p) {
            // ambil bom berdasarkan product_id
            $bill_of_material = BillOfMaterial::where('product_id', $product_p->id)->get();
            foreach ($bill_of_material as $bom) {
                // ambil bom_detail untuk mengetahui part & qty
                $bom_detail = BillOfMaterialDetail::where('bill_of_material_id', $bom->id)->get();
                foreach ($bom_detail as $detail) {
                    try {
                        // $production_planning_detail = ProductionPlanningDetail::where('production_planning_id', $product_p->id)->get();

                        $material_requirment = new MaterialRequirement();
                        $material_requirment->production_planning_id = $product_p->id;
                        $material_requirment->requirement_date = date('Y-m-d', strtotime(now()));
                        $material_requirment->save();
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
            }
        }
    }
}
