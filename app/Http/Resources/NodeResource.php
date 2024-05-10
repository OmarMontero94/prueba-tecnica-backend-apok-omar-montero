<?php

namespace App\Http\Resources;

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
    public function toArray($request) : array
    {
        return [
            'id' => $this->id,
            'parent' => $this->parent,
            'title' => $this->titleTranslation($this->id, $request->header("language")),
            'created_at' =>$this->timemezoneShift($this->created_at,$request->header("timezone")),
            'updated_at' => $this->timemezoneShift($this->updated_at,$request->header("timezone"))
        ];    
    }

   
}
