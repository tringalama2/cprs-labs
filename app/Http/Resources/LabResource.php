<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LabResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'label' => $this->label,
            'panel' => new PanelResource($this->whenLoaded('panel')),
            'order_column' => $this->order_column,
        ];
    }
}
