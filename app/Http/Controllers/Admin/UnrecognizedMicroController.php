<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnrecognizedMicroRequest;
use App\Models\Micro;
use App\Models\UnrecognizedMicro;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class UnrecognizedMicroController extends Controller
{
    public function edit(UnrecognizedMicro $unrecognizedMicro): View
    {
        return view('admin.unrecognized-micros.edit', compact('unrecognizedMicro'));
    }

    public function update(UnrecognizedMicro $unrecognizedMicro, UnrecognizedMicroRequest $request): RedirectResponse
    {
        $validated = $request->safe()->only(['label', 'panel_id']);

        DB::transaction(function () use ($validated, $unrecognizedMicro) {
            Micro::create(array_merge($validated, ['name' => $unrecognizedMicro->name, 'order_column' => 1000]));
            $unrecognizedMicro->delete();
        });

        return redirect()->route('admin.micro.index')->with(['message' => "{$validated['label']} micro has been saved."]);
    }
}
