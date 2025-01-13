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
        Schema::create('neighborhood_associations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete()->cascadeOnUpdate()->nullable();
            $table->foreignId('citizen_association_id')->constrained('citizen_associations')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('position', 50);
            $table->string('name', 100);
            $table->text('address');
            $table->string('phone', 16);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('neighborhood_associations');
    }
};
