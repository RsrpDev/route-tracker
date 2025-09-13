<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouteAssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'assignment_id' => $this->assignment_id,
            'route_id' => $this->route_id,
            'driver_id' => $this->driver_id,
            'vehicle_id' => $this->vehicle_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'assignment_status' => $this->assignment_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relaciones cuando se incluyan
            'route' => $this->whenLoaded('route', fn() => new RouteResource($this->route)),
            'driver' => $this->whenLoaded('driver', fn() => new DriverResource($this->driver)),
            'vehicle' => $this->whenLoaded('vehicle', fn() => new VehicleResource($this->vehicle)),
        ];
    }
}
