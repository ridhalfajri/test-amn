<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductionCapacity;
use App\Models\ProductionPlanning;
use App\Models\ProductionPlanningDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductPlanningController extends Controller
{
    public function index()
    {
        $product = Product::all();
        return view('production_planning', compact('product'));
    }
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->qty as $data) {
                if ($data == 0) {
                    continue;
                }
                $key = array_keys($request->qty, $data);

                $production_planning = new ProductionPlanning();
                $production_planning->product_id = $key[0];
                $production_planning->planned_qty = $data;

                $daily_capacity = ProductionCapacity::where('product_id', $key[0])->first();
                $production_planning->daily_capacity = $daily_capacity->capacity;
                $production_planning->plan_date = date('Y-m-d', strtotime(now()));
                $production_planning->save();

                $qty = $production_planning->planned_qty;
                $date = $production_planning->plan_date;
                while ($qty > $production_planning->daily_capacity) {
                    $production_planning_detail = new ProductionPlanningDetail();
                    $production_planning_detail->production_planning_id = $production_planning->id;
                    $production_planning_detail->production_date = $date;
                    $production_planning_detail->production_qty = $production_planning->daily_capacity;
                    $production_planning_detail->save();
                    $date = date('Y-m-d', strtotime("+1 day", strtotime($date)));
                    $qty -= $production_planning->daily_capacity;
                }
                $production_planning_detail = new ProductionPlanningDetail();
                $production_planning_detail->production_planning_id = $production_planning->id;
                $production_planning_detail->production_date = $date;
                $production_planning_detail->production_qty = $qty;
                $production_planning_detail->save();
            }
            DB::commit();
            return  'DATA BERHASIL DISIMPAN';
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
        }
    }
}
