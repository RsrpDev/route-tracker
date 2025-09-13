<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'subscription_id' => $this->subscription_id,
            'contract_id' => $this->contract_id,
            'billing_cycle' => $this->billing_cycle,
            'price_snapshot' => $this->price_snapshot,
            'platform_fee_rate' => $this->platform_fee_rate,
            'next_billing_date' => $this->next_billing_date,
            'subscription_status' => $this->subscription_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relaciones cuando se incluyan
            'enrollment' => $this->whenLoaded('enrollment', fn() => new EnrollmentResource($this->enrollment)),
            'payments' => $this->whenLoaded('payments', fn() => PaymentResource::collection($this->payments)),
        ];
    }
}
