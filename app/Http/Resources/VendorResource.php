<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      $vendorContacts =User::find($this->id)->vendorContacts;
      return [
        'vendor_id' => $this->id,
        'token' => $this->token,
        'name' => $this->name,
        'email' => $this->email,
        'number' => $this->number,
        'address' => $this->address,
      ];
    }
}
