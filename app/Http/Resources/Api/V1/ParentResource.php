<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'parent_id' => $this->parent_id,
            'account_id' => $this->account_id,
            'address' => $this->address,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relaciones cuando se incluyan
            'account' => $this->whenLoaded('account', fn() => new AccountResource($this->account)),
            'students' => $this->whenLoaded('students', fn() => StudentResource::collection($this->students)),
        ];
    }
}
