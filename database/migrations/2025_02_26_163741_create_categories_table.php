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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->check("type in ('needs', 'wants', 'savings', 'income')");
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('family_id')->nullable()->constrained();
            $table->timestamps();
            $table->unique(['name', 'user_id', 'family_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
