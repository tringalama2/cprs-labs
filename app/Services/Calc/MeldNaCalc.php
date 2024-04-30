<?php

namespace App\Services\Calc;

use App\Exceptions\InvalidCalculatorInputException;

class MeldNaCalc implements CalcInterface
{
    public function description(): string
    {
        return
            <<<'DESC'
            MELD Na (UNOS/OPTN)<br />
            <em>Quantifies end-stage liver disease for transplant planning with sodium.</em>
            DESC;
    }

    /**
     * @throws InvalidCalculatorInputException
     */
    public function result(array $inputLabs): float|int|string
    {
        if (array_values(self::inputLabs()) !== array_keys($inputLabs)) {
            throw new InvalidCalculatorInputException();
        }

        $inputLabs['INR'] = max($inputLabs['INR'], 1);
        $inputLabs['Na'] = max($inputLabs['Na'], 125);
        $inputLabs['Na'] = min($inputLabs['Na'], 137);
        $inputLabs['Cr'] = min($inputLabs['Cr'], 4);

        $meldI = round(0.957 * log($inputLabs['Cr']) + 0.378 * log($inputLabs['T-Bili']) + 1.120 * log($inputLabs['INR']) + 0.643,
            10) * 10;

        if ($meldI > 11) {
            return (int) round($meldI + 1.32 * (137 - $inputLabs['Na']) - (0.033 * $meldI * (137 - $inputLabs['Na'])));
        }

        return (int) round($meldI);
    }

    public function inputLabs(): array
    {
        return [
            'Cr',
            'T-Bili',
            'INR',
            'Na',
        ];
    }
}
