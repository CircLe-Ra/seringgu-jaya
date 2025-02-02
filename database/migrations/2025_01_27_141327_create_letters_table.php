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
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('letter_type_id')->constrained('letter_types')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('neighborhood_association_id')->constrained('neighborhood_associations')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('family_member_id')->constrained('family_members')->restrictOnDelete()->cascadeOnUpdate();
            $table->text('letter_file');
            $table->text('family_card_file');
            $table->text('resident_identification_card_file');
            $table->text('response_letter_file')->nullable();
            $table->boolean('submission_status')->default(false);
            $table->enum('status', ['draft', 'apply', 'process','reply'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
