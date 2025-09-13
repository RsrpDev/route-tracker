<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'provider_id' => $this->provider_id,
            'account_id' => $this->account_id,
            'provider_type' => $this->provider_type,
            'display_name' => $this->display_name,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'linked_school_id' => $this->linked_school_id,
            'default_commission_rate' => $this->default_commission_rate,
            'provider_status' => $this->provider_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relaciones cuando se incluyan
            'account' => $this->whenLoaded('account', fn() => new AccountResource($this->account)),
            'linked_school' => $this->whenLoaded('linkedSchool', fn() => new SchoolResource($this->linkedSchool)),
            'drivers' => $this->whenLoaded('drivers', fn() => DriverResource::collection($this->drivers)),
            'vehicles' => $this->whenLoaded('vehicles', fn() => VehicleResource::collection($this->vehicles)),
            'routes' => $this->whenLoaded('routes', fn() => RouteResource::collection($this->routes)),
        ];
    }
}
