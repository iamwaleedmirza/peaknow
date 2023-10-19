<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuids;
use App\Models\{Product,PlanDetail,MedicineVarient};

class NewPlan extends Model
{
    use HasFactory,HasUuids;

    public $table = "plans";

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime:m/d/Y : H:i',
        'updated_at' => 'datetime:m/d/Y : H:i',
    ];

    public function product() {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function plan_type() {
        return $this->hasOne(PlanDetail::class, 'id', 'plan_type_id');
    }

    public function medicine_variant() {
        return $this->hasOne(medicinevarient::class, 'id', 'medicine_varient_id');
    }
}
