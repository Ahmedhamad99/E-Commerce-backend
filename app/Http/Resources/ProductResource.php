<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'description'=>$this->description,
            'image'=>$this->image,
            'price'=>$this->price,
            'stock'=>$this->stock,
            'out_of_stock'=>$this->out_of_stock,
            'out_of_stock'=>$this->out_of_stock,

            'created_at'=> $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
