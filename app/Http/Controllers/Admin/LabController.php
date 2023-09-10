<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LabRequest;
use App\Models\Lab;

class LabController extends Controller
{
    public function store(LabRequest $request)
    {
        $lab = Lab::create($request->validated());

        return redirect()->route('admin.panel.show', $lab->panel_id)->with(['message' => "$lab->label lab has been saved."]);
    }

    public function create()
    {
        return view('admin.labs.create');
    }

    public function edit(Lab $lab)
    {
        return view('admin.labs.edit', compact('lab'));
    }

    public function update(LabRequest $request, Lab $lab)
    {
        $lab->update($request->validated());

        return redirect()->route('admin.panel.show', $lab->panel_id)->with(['message' => "{$request->validated()['label']} lab has been saved."]);
    }
}
