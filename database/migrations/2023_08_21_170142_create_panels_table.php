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
            $table->unsignedBigInteger('order_column')->nullable();
        });

        DB::table('panels')->insert([
            ['id' => 1, 'label' => 'CBC', 'order_column' => 1],
            ['id' => 2, 'label' => 'Morphology', 'order_column' => 2],
            ['id' => 3, 'label' => 'Chem', 'order_column' => 3],
            ['id' => 4, 'label' => 'LFT', 'order_column' => 4],
            ['id' => 5, 'label' => 'ABG', 'order_column' => 6],
            ['id' => 6, 'label' => 'Coag', 'order_column' => 7],
            ['id' => 7, 'label' => 'Cardiac', 'order_column' => 8],
            ['id' => 8, 'label' => 'UA', 'order_column' => 9],
            ['id' => 9, 'label' => 'Infectious', 'order_column' => 11],
            ['id' => 10, 'label' => 'Body Fluids', 'order_column' => 12],
            ['id' => 11, 'label' => 'Urine', 'order_column' => 13],
            ['id' => 12, 'label' => 'UDS', 'order_column' => 14],
            ['id' => 13, 'label' => 'Misc Chemistry', 'order_column' => 5],
            ['id' => 14, 'label' => 'Iron', 'order_column' => 15],
            ['id' => 15, 'label' => 'Vitamins', 'order_column' => 16],
            ['id' => 16, 'label' => 'Autoimmune', 'order_column' => 17],
            ['id' => 17, 'label' => 'Lipids', 'order_column' => 10],
            ['id' => 18, 'label' => 'Stool', 'order_column' => 18],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('panels');
    }
};
