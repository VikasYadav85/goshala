<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cow_sponsorships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cow_id')->constrained('cows')->cascadeOnDelete();
            $table->string('sponsor_name');
            $table->string('sponsor_email');
            $table->string('sponsor_phone')->nullable();
            $table->string('plan')->default('monthly'); // monthly|yearly|lifetime
            $table->unsignedInteger('amount');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status')->default('pending'); // pending|active|expired|cancelled
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['cow_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cow_sponsorships');
    }
};
