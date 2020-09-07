<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [            
            'type' => 'products',
            'id' => $this->when(isset($this->id), $this->id),
            'attributes' => [
                'SKU' => $this->when(isset($this->SKU), $this->SKU),
                'name' => $this->name,
                'stock' => $this->stock,
                'price' => $this->price,
                'description' => $this->description,
                'img' => $this->img
            ], 
            'relationships' => [
                'users' => [
                    'data' => [
                        'type' => 'users',
                        'id' => $this->user_id
                    ]
                ]
            ],            
            'links' => [
                'self' => $request->fullUrl() 
            ]
            
        ];
    }
    public function withResponse($request, $response)
    {
        $response->header('Content-Type', 'application/vnd.api+json');
    }
}
