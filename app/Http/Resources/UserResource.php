<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'prenom' => $this->prenom,
            'cin' => $this->cin,
            'cnss' => $this->cnss,
            'post' => $this->post,
            'date_de_naissance' => $this->date_de_naissance,
            'genre' => $this->genre,
            'salaire' => $this->salaire,
            'date_embauche' => $this->date_embauche,
            'tel' => $this->tel,
            'ville' => $this->ville,
            'adresse' => $this->adresse,
            'image' => $this->image,
            'email' => $this->email,
            'role' => $this->role,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'department' => new DepartmentResource($this->whenLoaded('department')),
            'contracts' => ContractResource::collection($this->whenLoaded('contracts')),
            'conges' => CongeResource::collection($this->whenLoaded('conges')),
        ];
    }
}
