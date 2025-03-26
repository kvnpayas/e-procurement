<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use App\Models\VendorBank;
use App\Models\VendorClass;
use App\Models\VendorNature;
use App\Models\ProjectbidResult;
use App\Models\ProjectbidProtest;
use App\Models\VendorTopCustomer;
use Laravel\Passport\HasApiTokens;
use App\Models\ProjectBidVendorStatus;
use App\Models\ProjectbidEnvelopeStatus;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role_id',
        'address',
        'number',
        'tin_no',
        'dti_sec_no',
        'password',
        'default_password',
        'token',
        'active',
        'company_profile',
        'crtd_user',
        'upd_user',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function vendorContacts()
    {
      return $this->hasMany(VendorContact::class, 'vendor_id');
    }

    public function vendorNatures()
    {
      return $this->hasMany(VendorNature::class, 'vendor_id');
    }

    public function vendorClasses()
    {
      return $this->hasMany(VendorClass::class, 'vendor_id');
    }
    public function vendorBank()
    {
      return $this->hasOne(VendorBank::class, 'vendor_id');
    }
    public function vendorTopCustomers()
    {
      return $this->hasMany(VendorTopCustomer::class, 'vendor_id');
    }

    public function role()
    {
      return $this->belongsTo(Role::class, 'role_id');
    }

    // Bidding
    public function biddings()
    {
      return $this->belongsToMany(ProjectBidding::class, 'projectbid_vendors', 'vendor_id', 'bidding_id')
                  ->withPivot('status', 'confirm', 'response_date', 'crtd_user', 'upd_user');
    }

    public function bidStatus()
    {
      return $this->hasMany(ProjectBidVendorStatus::class, 'vendor_id');
    }
    public function envelopeStatus()
    {
      return $this->hasMany(ProjectbidEnvelopeStatus::class, 'vendor_id');
    }

    public function finalResult()
    {
      return $this->hasMany(ProjectbidResult::class, 'vendor_id');
    }
    public function winBids()
    {
      return $this->hasMany(ProjectbidApproval::class, 'winner_id');
    }

    public function protests()
    {
      return $this->belongsToMany(ProjectbidProtest::class, 'protest_vendors', 'vendor_id', 'protest_id')
        ->withPivot('protest_message', 'status')
        ->withTimestamps();
    }

    public function vendorStatus()
    {
      return $this->hasMany(ProjectBidVendorStatus::class, 'vendor_id');
    }

}
