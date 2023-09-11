<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MicroRequest;
use App\Models\Micro;

class MicroController extends Controller
{
    public function index()
    {
        $micros = Micro::query()->orderBy('order_column')->get();

        return view('admin.micros.index', compact('micros'));
    }

    public function store(MicroRequest $request)
    {
        $micro = Micro::create($request->validated());

        return redirect()->route('admin.micro.index')->with(['message' => "$micro->label micro has been saved."]);
    }

    public function create()
    {
        return view('admin.micros.create');
    }

    public function edit(Micro $micro)
    {
        return view('admin.micros.edit', compact('micro'));
    }

    public function update(MicroRequest $request, Micro $micro)
    {
        $micro->update($request->validated());

        return redirect()->route('admin.micro.index')->with(['message' => "{$request->validated()['label']} micro has been saved."]);

    }
}
