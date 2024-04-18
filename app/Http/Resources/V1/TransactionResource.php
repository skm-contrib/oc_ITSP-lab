<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            "wallet_id" => $this->wallet_id,
            'amount' => $this->amount,
            "transaction_type" => $this->transaction_type,
            "category" => $this->category,
            "description" => $this->description,
            "transaction_date" => $this->transaction_date,
            'category' => new ExpenseCategoryResource($this->whenLoaded('category')),
        ];
    }
}
