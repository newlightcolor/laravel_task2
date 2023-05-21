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
        Schema::create('task_tag', function (Blueprint $table) {
            $table->id();
            $table->char('name', 30);
            $table->integer('max_select_content')->nullable(true);
            $table->tinyInteger('use_order_by_column')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_tag');
    }
};
