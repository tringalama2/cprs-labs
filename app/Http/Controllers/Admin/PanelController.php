<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PanelRequest;
use App\Models\Panel;

class PanelController extends Controller
{
    public function index()
    {
        $panels = Panel::query()->with([
            'labs' => function ($query) {
                $query->orderBy('order_column');
            },
        ])->orderBy('order_column')->get();

        return view('admin.panels.index', compact('panels'));
    }

    public function store(PanelRequest $request)
    {
        $panel = Panel::create($request->validated());

        return redirect()->route('admin.panel.index')->with(['message' => "{$panel->label} panel has been saved."]);
    }

    public function create()
    {
        return view('admin.panels.create');
    }

    public function show(Panel $panel)
    {
        $panel->load([
            'labs' => function ($query) {
                $query->orderBy('order_column');
            },
        ]);

        return view('admin.panels.show', compact('panel'));
    }

    public function edit(Panel $panel)
    {
        return view('admin.panels.edit', compact('panel'));
    }

    public function update(PanelRequest $request, Panel $panel)
    {
        $panel->update($request->validated());

        return redirect()->route('admin.panel.index')->with(['message' => "{$request->validated()['label']} panel has been saved."]);
    }
}
