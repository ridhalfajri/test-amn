<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionPlanningDetail extends Model
{
    use HasFactory;
    protected $table = 'production_planning_detail';
    public $timestamps = false;
}
