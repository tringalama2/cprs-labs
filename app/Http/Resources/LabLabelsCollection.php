<?php

namespace App\Http\Resources;

use App\Models\Lab;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LabLabelsCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => Lab::leftJoin('panels', 'labs.panel_id', '=', 'panels.id')
                ->select('labs.name', 'labs.label', 'panels.label as panel')
                ->orderBy('panels.order_column')
                ->orderBy('labs.order_column')
                ->get()->keyBy('name'),
        ];
    }
}
