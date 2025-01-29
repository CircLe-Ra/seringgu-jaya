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
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_card_id')->constrained('family_cards')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('resident_identification_number', 16)->unique();
            $table->enum('position', ['patriarch','familiar']);
            $table->string('name',100);
            $table->enum('gender', ['M','F']);
            $table->string('birth_place');
            $table->date('birth_date');
            $table->foreignId('religion_id')->constrained('religions')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('education_id')->constrained('education')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('employment_id')->constrained('employments')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('blood_group_id')->constrained('blood_groups')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_members');
    }
};
