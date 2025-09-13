<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'account_id' => $this->account_id,
            'account_type' => $this->account_type,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'id_number' => $this->id_number,
            'account_status' => $this->account_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relaciones cuando se incluyan
            'parent_profile' => $this->whenLoaded('parentProfile', fn() => new ParentResource($this->parentProfile)),
            'provider' => $this->whenLoaded('provider', fn() => new ProviderResource($this->provider)),
            'school' => $this->whenLoaded('school', fn() => new SchoolResource($this->school)),
        ];
    }
}
