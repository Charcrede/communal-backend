<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->longText('content');
            $table->uuid('rubric_id');
            $table->timestamps();

            $table->foreign('rubric_id')->references('id')->on('rubrics')->onDelete('cascade');
            $table->index('rubric_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
