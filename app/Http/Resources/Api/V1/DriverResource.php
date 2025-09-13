<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'driver_id' => $this->driver_id,
            'provider_id' => $this->provider_id,
            'given_name' => $this->given_name,
            'family_name' => $this->family_name,
            'id_number' => $this->id_number,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'license_number' => $this->license_number,
            'license_category' => $this->license_category,
            'license_expiration' => $this->license_expiration,
            'years_experience' => $this->years_experience,
            'driver_status' => $this->driver_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relaciones cuando se incluyan
            'provider' => $this->whenLoaded('provider', fn() => new ProviderResource($this->provider)),
        ];
    }
}
