<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('cow_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('breed')->nullable();
            $table->string('age')->nullable(); // e.g. "5 years"
            $table->string('gender')->default('female'); // female|male
            $table->string('color')->nullable();
            $table->date('rescued_at')->nullable();
            $table->text('rescue_story')->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->unsignedInteger('monthly_sponsorship_amount')->default(2100);
            $table->boolean('is_available_for_sponsorship')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('active'); // active|under_treatment|passed_away
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['status', 'is_available_for_sponsorship']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cows');
    }
};
