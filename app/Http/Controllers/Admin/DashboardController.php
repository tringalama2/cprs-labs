<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\Panel;
use App\Models\UnparsableLab;
use App\Models\UnrecognizedLab;
use App\Models\UnrecognizedMicro;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $panels = Panel::query()->orderBy('order_column')->get();
        $labs = Lab::query()
            ->join('panels', 'panels.id', '=', 'labs.panel_id')
            ->orderBy('panels.order_column')
            ->orderBy('labs.order_column')
            ->get(['labs.label', 'panels.label as panel']);
        $unparsableLabs = UnparsableLab::latest()->get();
        $unrecognizedLabs = UnrecognizedLab::latest()->get();
        $unrecognizedMicro = UnrecognizedMicro::latest()->get();

        return view('admin.dashboard', compact(
            'panels',
            'labs',
            'unparsableLabs',
            'unrecognizedLabs',
            'unrecognizedMicro',
        ));
    }
}
