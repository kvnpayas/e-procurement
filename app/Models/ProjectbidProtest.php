<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectbidProtest extends Model
{
    use HasFactory;

   protected $fillable = [
    'bidding_id',
    'winning_bidder_id',
    'protest_deadline_date',
    'status',
   ];

   public function bid()
   {
     return $this->belongsTo(ProjectBidding::class, 'bidding_id');
   }

   public function vendors()
   {
     return $this->belongsToMany(User::class, 'protest_vendors', 'protest_id', 'vendor_id')
       ->withPivot('protest_message', 'status')
       ->withTimestamps();
   }

   public function winningVendor()
   {
     return $this->belongsTo(User::class, 'winning_bidder_id');
   }
}
