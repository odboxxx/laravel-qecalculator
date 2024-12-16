<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('qec_history', function (Blueprint $table) {
            $table->id();
            $table->decimal('a','20','5');
            $table->decimal('b','20','5');
            $table->decimal('c','20','5');
            $table->decimal('d','20','5');
            $table->decimal('x1','20','5')->nullable(true);
            $table->decimal('x2','20','5')->nullable(true);
            $table->tinyInteger('roots_quant');
            $table->dateTime('date');
            $table->tinyInteger('export_status',false,true)->default(0);
            $table->index('export_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    { 
        Schema::dropIfExists('qec_history');
    }
};
