<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Product,PlanDetail,MedicineVarient};

class PeaksPromoCode extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function product() {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function plan_type() {
        return $this->hasOne(PlanDetail::class, 'id', 'plan_type_id');
    }

    public function medicine_variant() {
        return $this->hasOne(medicinevarient::class, 'id', 'medicine_variant_id');
    }
}
