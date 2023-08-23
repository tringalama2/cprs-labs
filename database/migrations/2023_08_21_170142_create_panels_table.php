<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panels', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->unsignedBigInteger('sort_id')->nullable();
        });

        DB::table('panels')->insert([
            ['id' => 1, 'label' => 'CBC', 'sort_id' => 1],
            ['id' => 2, 'label' => 'Morphology', 'sort_id' => 2],
            ['id' => 3, 'label' => 'Chem', 'sort_id' => 3],
            ['id' => 4, 'label' => 'LFT', 'sort_id' => 4],
            ['id' => 5, 'label' => 'ABG', 'sort_id' => 5],
            ['id' => 6, 'label' => 'Coag', 'sort_id' => 7],
            ['id' => 7, 'label' => 'Cardiac', 'sort_id' => 8],
            ['id' => 8, 'label' => 'UA', 'sort_id' => 9],
            ['id' => 9, 'label' => 'Infectious', 'sort_id' => 10],
            ['id' => 10, 'label' => 'Body Fluids', 'sort_id' => 11],
            ['id' => 11, 'label' => 'Urine', 'sort_id' => 12],
            ['id' => 12, 'label' => 'UDS', 'sort_id' => 13],
            ['id' => 13, 'label' => 'Misc Chemistry', 'sort_id' => 5],
            ['id' => 14, 'label' => 'Iron', 'sort_id' => 14],
            ['id' => 15, 'label' => 'Vitamins', 'sort_id' => 15],
            ['id' => 16, 'label' => 'Autoimmune', 'sort_id' => 16],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('panels');
    }
};
