<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->string('invitee_name');
            $table->string('invitee_email');
            $table->string('invitee_phone')->nullable();
            $table->string('occasion');
            $table->date('event_date')->nullable();
            $table->string('event_time')->nullable();
            $table->string('venue')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('pending'); // pending|sent|failed
            $table->timestamp('sent_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('invitee_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
