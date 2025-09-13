<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'student_id' => $this->student_id,
            'parent_id' => $this->parent_id,
            'given_name' => $this->given_name,
            'family_name' => $this->family_name,
            'identity_number' => $this->identity_number,
            'birth_date' => $this->birth_date,
            'school_id' => $this->school_id,
            'grade' => $this->grade,
            'shift' => $this->shift,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relaciones cuando se incluyan
            'parent' => $this->whenLoaded('parent', fn() => new ParentResource($this->parent)),
            'school' => $this->whenLoaded('school', fn() => new SchoolResource($this->school)),
            'enrollments' => $this->whenLoaded('enrollments', fn() => EnrollmentResource::collection($this->enrollments)),
        ];
    }
}
