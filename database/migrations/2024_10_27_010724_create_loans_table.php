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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 15, 2);
            $table->decimal('interest_rate', 5, 2);
            $table->integer('duration');
            $table->foreignId('lender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('borrower_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes(); // Adds deleted_at column for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
