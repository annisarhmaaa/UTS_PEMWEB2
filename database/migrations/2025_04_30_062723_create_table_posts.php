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
        Schema::create('table_posts', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'title');
            $table->string(column: 'slug')->unique();
            $table->text(column: 'content');
            $table->string(column: 'image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_posts');
    }
};
