<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'user_id' => $this->user_id, // Assuming there's a user_id field
            'contract_type_id' => $this->type, // Example field
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
