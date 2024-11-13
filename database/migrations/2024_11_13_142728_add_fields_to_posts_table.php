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
        Schema::table('posts', function (Blueprint $table) {
            $table->string('summary', 50)->nullable()->after('body'); // Resumen, máximo 50 caracteres
            $table->string('slug', 255)->nullable()->unique()->after('title'); // Slug único, en snake_case
            $table->enum('status', ['published', 'draft', 'archived', 'pending'])->default('draft')->after('slug'); // Estado del post
            $table->integer('reading_time')->nullable()->after('status'); // Tiempo estimado de lectura en minutos

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['summary', 'slug', 'status', 'reading_time']);
        });
    }
};
