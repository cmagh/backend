<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ErrorCollection extends ResourceCollection
{
    public static $wrap = 'errors';
    
    public function toArray($request)
    {
        return [
            'errors' => ErrorResource::collection($this->collection)
        ];
    }

    public function withResponse($request, $response)
    {
        $response->header('Content-Type', 'application/vnd.api+json');
    }
}
