<?php

namespace App\Console\Commands;

use App\Services\Calculators\Core\LabValueResolver;
use App\Services\LabBuilder;
use Illuminate\Console\Command;

class DebugCalculators extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:calculators {--file=test.comprehensive.txt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug calculator mappings and lab resolution';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = $this->option('file');
        $filePath = resource_path($filename);

        if (! file_exists($filePath)) {
            $this->error("File not found: {$filePath}");

            return 1;
        }

        $input = file_get_contents($filePath);

        $labBuilder = new LabBuilder($input);
        $labBuilder->build();

        $labs = $labBuilder->getLabCollection();
        $calculatedValues = $labBuilder->getCalculatedValues();
        $resolver = new LabValueResolver($labs);

        $this->info("=== DEBUG CALCULATOR SYSTEM ===\n");

        $this->info('Available Lab Names:');
        $labNames = $resolver->getAvailableLabNames();
        foreach ($labNames as $name) {
            $this->line("  - {$name}");
        }

        $this->info("\n=== TESTING FENA FIELDS ===");
        $fenaFields = ['CREATININE,blood', 'SODIUM,Blood', 'CREATININE,Urine', 'SODIUM,Urine'];

        foreach ($fenaFields as $field) {
            $value = $resolver->getLatestValue($field);

            $this->info("Field: {$field}");
            $this->line('  Value: '.($value ?? 'NULL'));

            // Check if this lab name exists in our labs
            $lab = $labs->where('name', $field)->first();
            if ($lab) {
                $this->line("  Found lab: {$field} = {$lab['result']}");
            } else {
                $this->line('  Lab not found in data');
            }
            $this->line('');
        }

        $this->info('=== TESTING MELD FIELDS ===');
        $meldFields = ['BILIRUBIN,TOTAL,Blood', 'CREATININE,blood', 'INR,blood'];

        foreach ($meldFields as $field) {
            $value = $resolver->getLatestValue($field);

            $this->info("Field: {$field}");
            $this->line('  Value: '.($value ?? 'NULL'));

            // Check if this lab name exists in our labs
            $lab = $labs->where('name', $field)->first();
            if ($lab) {
                $this->line("  Found lab: {$field} = {$lab['result']}");
            } else {
                $this->line('  Lab not found in data');
            }
            $this->line('');
        }

        $this->info('=== CALCULATED VALUES ===');
        $this->info('Total calculations: '.$calculatedValues->count());

        foreach ($calculatedValues as $result) {
            $array = $result->toArray();
            $this->info("Calculator: {$array['display_name']}");
            $this->line("  Value: {$array['display_value']}");
            $this->line("  Interpretation: {$array['interpretation']}");
            $this->line('');
        }

        return 0;
    }
}
