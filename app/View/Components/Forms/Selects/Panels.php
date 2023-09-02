<?php

namespace App\View\Components\Forms\Selects;

use App\Models\Panel;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Panels extends Component
{
    public function __construct()
    {
    }

    public function render(): View
    {
        return view('components.forms.select', [
            'options' => Panel::query()
                ->orderBy('order_column')
                ->pluck('label', 'id')
                ->toArray(),
        ]);
    }
}
