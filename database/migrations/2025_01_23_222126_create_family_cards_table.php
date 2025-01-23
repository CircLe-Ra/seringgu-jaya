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
        Schema::create('family_cards', function (Blueprint $table) {
            $table->id();
            $table->string('family_card_number', 16)->unique();
            $table->string('head_of_family', 100);
            $table->foreignId('province_id')->constrained('provinces')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('regency_id')->constrained('regencies')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('district_id')->constrained('districts')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('sub_district_id')->constrained('sub_districts')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('citizen_association_id')->constrained('citizen_associations')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('neighborhood_association_id')->constrained('neighborhood_associations')->restrictOnDelete()->cascadeOnUpdate();
            $table->text('address');
            $table->integer('postal_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_cards');
    }
};
