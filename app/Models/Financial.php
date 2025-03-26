<?php

namespace App\Models;

use App\Models\FinancialVendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Financial extends Model
{
    use HasFactory;
    protected $fillable = [
      'inventory_id', 
      'description', 
      'class_id', 
      'uom', 
      'unit_price', 
      'unit_cost', 
      'available_quantity', 
      'quantity_on_hand', 
      'scrap', 
      'crtd_user', 
      'updtd_user', 
    ];

    public function biddings()
    {
      return $this->belongsToMany(ProjectBidding::class, 'projectbid_financials', 'financial_id', 'bidding_id')
                  ->withPivot('crtd_user', 'upd_user', 'bid_price', 'quantity', 'remarks')
                  ->withTimestamps();
    }
    public function financialVendors()
    {
        return $this->hasMany(FinancialVendor::class);
    }
}
