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
        Schema::create('task_tag_content', function (Blueprint $table) {
            $table->id();
            $table->integer('tag_id');
            $table->char('content', 100);
            $table->char('content_color', 10)->nullable(true);
            $table->char('for_order_by', 100)->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_tag_content');
    }
};
