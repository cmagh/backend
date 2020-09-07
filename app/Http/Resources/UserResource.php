<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'type' => 'users',
            'id' => $this->when(isset($this->id), $this->id),
            'attributes' => [
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'phone' => $this->phone,
                'birthday' => $this->birthday,                
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
