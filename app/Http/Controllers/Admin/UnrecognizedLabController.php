<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnrecognizedLabRequest;
use App\Models\Lab;
use App\Models\UnrecognizedLab;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class UnrecognizedLabController extends Controller
{
    public function edit(UnrecognizedLab $unrecognizedLab): View
    {
        return view('admin.unrecognized-labs.edit', compact('unrecognizedLab'));
    }

    public function update(UnrecognizedLab $unrecognizedLab, UnrecognizedLabRequest $request): RedirectResponse
    {
        $validated = $request->safe()->only(['label', 'panel_id']);

        DB::transaction(function () use ($validated, $unrecognizedLab) {
            Lab::create(array_merge($validated, ['name' => $unrecognizedLab->name, 'order_column' => 1000]));
            $unrecognizedLab->delete();
        });

        return redirect()->route('admin.panel.show', $validated['panel_id'])->with(['message' => "{$validated['label']} lab has been saved."]);
    }
}
