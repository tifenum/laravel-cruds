<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CongeResource extends JsonResource
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
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin,
            'etat' => $this->etat,
            'user_id' => $this->user_id,
        ];
    }
}
