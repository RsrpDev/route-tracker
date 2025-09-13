<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'school_id' => $this->school_id,
            'account_id' => $this->account_id,
            'legal_name' => $this->legal_name,
            'rector_name' => $this->rector_name,
            'nit' => $this->nit,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relaciones cuando se incluyan
            'account' => $this->whenLoaded('account', fn() => new AccountResource($this->account)),
        ];
    }
}
