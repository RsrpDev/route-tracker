<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'vehicle_id' => $this->vehicle_id,
            'provider_id' => $this->provider_id,
            'plate' => $this->plate,
            'brand' => $this->brand,
            'model_year' => $this->model_year,
            'capacity' => $this->capacity,
            'soat_expiration' => $this->soat_expiration,
            'insurance_expiration' => $this->insurance_expiration,
            'technical_inspection_expiration' => $this->technical_inspection_expiration,
            'vehicle_status' => $this->vehicle_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relaciones cuando se incluyan
            'provider' => $this->whenLoaded('provider', fn() => new ProviderResource($this->provider)),
        ];
    }
}
