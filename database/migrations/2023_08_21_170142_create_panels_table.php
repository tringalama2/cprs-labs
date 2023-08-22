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
            ['id' => 6, 'label' => 'Coag', 'sort_id' => 6],
            ['id' => 7, 'label' => 'Cardiac', 'sort_id' => 7],
            ['id' => 8, 'label' => 'UA', 'sort_id' => 8],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('panels');
    }
};
