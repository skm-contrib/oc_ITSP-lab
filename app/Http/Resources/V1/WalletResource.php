<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "user_id" => $this->user_id,
            "id" => $this->id,
            "wallet_name" => $this->wallet_name,
            "currency" => $this->currency,
            "balance" => $this->balance,
            "color" => $this->color,
        ];
    }
}
