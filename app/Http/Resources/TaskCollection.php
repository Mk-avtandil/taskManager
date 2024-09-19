<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Request;

class TaskCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return ["data" => $this->collection];
    }

}
