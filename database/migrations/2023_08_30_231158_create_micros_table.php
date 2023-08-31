<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('micros', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('label');
            $table->unsignedBigInteger('order_column')->nullable();
        });

        DB::table('micros')->insert([
            ['name' => 'BLOOD CULTURE SET #1', 'label' => 'Blood Culture #1', 'order_column' => 1],
            ['name' => 'BLOOD CULTURE SET #2', 'label' => 'Blood Culture #2', 'order_column' => 2],
            ['name' => 'C&S,STOOL', 'label' => 'Stool Culture', 'order_column' => 3],
            ['name' => 'C&S,CSF', 'label' => 'CSF Culture', 'order_column' => 4],
            ['name' => 'FUNGAL CULTURE,CSF', 'label' => 'CSF Culture, Fungal', 'order_column' => 5],
            ['name' => 'AFB CULTURE/SMEAR,BRONCHIAL', 'label' => 'AFB, Bronchial', 'order_column' => 6],
            ['name' => 'FUNGAL CULTURE,BRONCHIAL', 'label' => 'Bronchial Culture, Fungal', 'order_column' => 7],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('micros');
    }
};
