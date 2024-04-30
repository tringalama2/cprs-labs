<?php

use App\Services\Calc\MeldNaCalc;

test('test', function () {
    $calc = new MeldNaCalc();
    dd($calc->result([
        'Cr' => 1,
        'T-Bili' => 1.2,
        'INR' => 1.2,
        'Na' => 130,
    ]));
});

test('it can limit input variables', function () {
    $calc = new MeldNaCalc();
    expect($calc->result([
        'Cr' => 8,
        'T-Bili' => 18.2,
        'INR' => 2.2,
        'Na' => 122,
    ]))->toEqual($calc->result([
        'Cr' => 4,
        'T-Bili' => 18.2,
        'INR' => 2.2,
        'Na' => 127,
    ]))->and($calc->result([
        'Cr' => 8,
        'T-Bili' => 18.2,
        'INR' => 2.2,
        'Na' => 140,
    ]))->toEqual($calc->result([
        'Cr' => 4,
        'T-Bili' => 18.2,
        'INR' => 2.2,
        'Na' => 137,
    ]));
});
