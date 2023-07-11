<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PoiResource extends JsonResource
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
            "nome" => ($this->poi_it->nome ? $this->poi_it->nome : "NO_NAME") . " " . $this->localita->nome,
            "id" => $this->id 
        ];
    }
}
