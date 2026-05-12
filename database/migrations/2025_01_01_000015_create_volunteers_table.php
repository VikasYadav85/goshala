<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('India');
            $table->string('occupation')->nullable();
            $table->json('areas_of_interest')->nullable(); // ['feeding','rescue','events','social_media','fundraising']
            $table->json('availability')->nullable(); // ['weekdays','weekends','evenings']
            $table->text('previous_experience')->nullable();
            $table->text('motivation')->nullable();
            $table->string('referral_source')->nullable();
            $table->string('status')->default('pending'); // pending|approved|active|inactive|rejected
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};
