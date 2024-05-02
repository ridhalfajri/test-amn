<?php

namespace App\Http\Controllers;

use App\Models\BillOfMaterial;
use App\Models\BillOfMaterialDetail;
use App\Models\MaterialRequirement;
use App\Models\MaterialRequirementBom;
use App\Models\MaterialRequirementDetail;
use App\Models\ProductionPlanning;
use App\Models\ProductionPlanningDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialRequirementController extends Controller
{
    // public function index()
    // {
    //     // $production_planning = ProductionPlanning::all();
    //     // // loop production planning
    //     // foreach ($production_planning as $product_p) {
    //     //     // ambil bom berdasarkan product_id
    //     //     $bill_of_material = BillOfMaterial::where('product_id', $product_p->id)->get();
    //     //     foreach ($bill_of_material as $bom) {
    //     //         // ambil bom_detail untuk mengetahui part & qty
    //     //         $bom_detail = BillOfMaterialDetail::where('bill_of_material_id', $bom->id)->get();
    //     //         foreach ($bom_detail as $detail) {
    //     //             try {
    //     //                 // $production_planning_detail = ProductionPlanningDetail::where('production_planning_id', $product_p->id)->get();

    //     //                 $material_requirment = new MaterialRequirement();
    //     //                 $material_requirment->production_planning_id = $product_p->id;
    //     //                 $material_requirment->requirement_date = date('Y-m-d', strtotime(now()));
    //     //                 $material_requirment->save();
    //     //             } catch (\Throwable $th) {
    //     //                 //throw $th;
    //     //             }
    //     //         }
    //     //     }
    //     // }
    // }
    public function index1()
    {
        //tahap 1
        $production_planning = ProductionPlanning::all();
        DB::beginTransaction();
        try {
            foreach ($production_planning as $product_p) {
                $production_planning_detail = ProductionPlanningDetail::where('production_planning_Id', $product_p->id)->get();
                foreach ($production_planning_detail as $product_pd) {
                    //tahap 2
                    $bill_of_material = BillOfMaterial::where('product_id', $product_p->id)->first();
                    $material_requirement = new MaterialRequirement();
                    $material_requirement->production_planning_id = $product_p->id;
                    $material_requirement->requirement_date = $product_pd->production_date;
                    $material_requirement->save();

                    $material_requirement_bom = new MaterialRequirementBom();
                    $material_requirement_bom->material_requirement_id = $material_requirement->id;
                    $material_requirement_bom->bill_of_material_id = $bill_of_material->id;
                    $material_requirement_bom->save();

                    $bill_of_material_detail = BillOfMaterialDetail::where('bill_of_material_id', $bill_of_material->id)->get();
                    foreach ($bill_of_material_detail as $bom_detail) {
                        $find = MaterialRequirementDetail::where('part_id', $bom_detail->id)->where('material_requirement_id', $material_requirement->id)->count();
                        if ($find) {
                            continue;
                        }

                        $material_requirement_detail = new MaterialRequirementDetail();
                        $material_requirement_detail->material_requirement_id = $material_requirement->id;
                        $material_requirement_detail->part_id = $bom_detail->part_id;
                        $material_requirement_detail->qty = $product_pd->production_qty * $bom_detail->qty;
                        $material_requirement_detail->requirement_date = $product_pd->production_date;
                        $material_requirement_detail->save();
                    }
                }
            }
            DB::commit();
            return "berhasil";
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
        }
    }
}
