<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'payment_id' => $this->payment_id,
            'subscription_id' => $this->subscription_id,
            'period_start' => $this->period_start,
            'period_end' => $this->period_end,
            'amount_total' => $this->amount_total,
            'platform_fee' => $this->platform_fee,
            'provider_amount' => $this->provider_amount,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'paid_at' => $this->paid_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relaciones cuando se incluyan
            'subscription' => $this->whenLoaded('subscription', fn() => new SubscriptionResource($this->subscription)),
        ];
    }
}
