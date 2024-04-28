<?php

namespace App\Http\Controllers;

use App\Models\MaterialRequirement;
use App\Models\MaterialRequirementDetail;
use App\Models\Part;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseRequestController extends Controller
{
    public function index()
    {
        return view('purchase_request');
    }
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $material_requirement = MaterialRequirement::all();
            foreach ($material_requirement as $material) {
                $total = 0;
                $purchase_request = new PurchaseRequest();
                $purchase_request->vendor = $request->vendor;
                $purchase_request->pr_date = date('Y-m-d', strtotime(now()));
                $purchase_request->delivery_date = date('Y-m-d', strtotime(now()));
                $purchase_request->material_requirement_id = $material->id;
                $purchase_request->total_price = 0;
                $purchase_request->save();
                $total_qty_part = MaterialRequirementDetail::select('part_id', DB::raw('SUM(qty) as total_qty'))
                    ->where('material_requirement_id', $material->id)
                    ->groupBy('part_id')
                    ->get();
                foreach ($total_qty_part as $qty_part) {
                    $part_price = Part::select('price')->where('id', $qty_part->part_id)->first();
                    $purchase_request_detail = new PurchaseRequestDetail();
                    $purchase_request_detail->purchase_request_id = $purchase_request->id;
                    $purchase_request_detail->part_id = $qty_part->part_id;
                    $purchase_request_detail->qty = $qty_part->total_qty;
                    $purchase_request_detail->price = $part_price->price;
                    $purchase_request_detail->total = $purchase_request_detail->qty * $purchase_request_detail->price;
                    $purchase_request_detail->save();
                    $total += $purchase_request_detail->total;
                }
                $purchase_request->total_price = $total;
                $purchase_request->save();
            }
            DB::commit();
            return  'DATA BERHASIL DISIMPAN';
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
        }
    }
}
