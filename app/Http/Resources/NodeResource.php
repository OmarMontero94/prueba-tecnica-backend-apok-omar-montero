<?php

namespace App\Http\Resources;

use NumberFormatter;

use Illuminate\Http\Resources\Json\JsonResource;


use App\Traits\FormaterTrait;

class NodeResource extends JsonResource
{
    use FormaterTrait;
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
            'parent' => $this->parent,
            'title' => $this->titleTranslation($this->id, $this->language),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];    
    }

   
}
