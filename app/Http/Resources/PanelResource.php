<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PanelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'label' => $this->label,
            'labs' => LabResource::collection($this->whenLoaded('labs')),
            'order_column' => $this->order_column,
        ];
    }
}
