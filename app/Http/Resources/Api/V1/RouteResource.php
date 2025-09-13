<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'route_id' => $this->route_id,
            'provider_id' => $this->provider_id,
            'route_name' => $this->route_name,
            'origin_address' => $this->origin_address,
            'destination_address' => $this->destination_address,
            'capacity' => $this->capacity,
            'monthly_price' => $this->monthly_price,
            'active_flag' => $this->active_flag,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relaciones cuando se incluyan
            'provider' => $this->whenLoaded('provider', fn() => new ProviderResource($this->provider)),
            'assignments' => $this->whenLoaded('assignments', fn() => RouteAssignmentResource::collection($this->assignments)),
            'enrollments' => $this->whenLoaded('enrollments', fn() => EnrollmentResource::collection($this->enrollments)),

            // Contadores para el endpoint show
            'assignments_count' => $this->when(isset($this->assignments_count), $this->assignments_count),
            'enrollments_count' => $this->when(isset($this->enrollments_count), $this->enrollments_count),
        ];
    }
}
