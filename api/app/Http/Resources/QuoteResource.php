<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class QuoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return
            [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'more_info' => $this->more_info,
                'part' => Storage::get($this->part->url),
            ];
    }
}
