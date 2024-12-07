<?php

use App\Http\Resources\LabLabelsCollection;
use App\Http\Resources\LabResource;
use App\Http\Resources\PanelResource;
use App\Models\Lab;
use App\Models\Panel;
use Illuminate\Support\Facades\Route;

Route::get('/panels', function () {
    return PanelResource::collection(Panel::with('labs')->get());
});

Route::get('/labs', function () {
    return LabResource::collection(Lab::with('panel')->get());
});

Route::get('/labels', function () {
    return new LabLabelsCollection(Lab::all());
});
