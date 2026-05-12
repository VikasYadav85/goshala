<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->foreignId('donation_category_id')->nullable()->constrained('donation_categories')->nullOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained('campaigns')->nullOnDelete();
            $table->foreignId('cow_id')->nullable()->constrained('cows')->nullOnDelete();

            // Donor info (denormalised so historical donations are immutable)
            $table->string('donor_name');
            $table->string('donor_email');
            $table->string('donor_phone')->nullable();
            $table->string('donor_pan')->nullable(); // for 80G receipt
            $table->text('donor_address')->nullable();
            $table->string('donor_city')->nullable();
            $table->string('donor_state')->nullable();
            $table->string('donor_pincode')->nullable();
            $table->string('donor_country')->default('India');

            $table->unsignedBigInteger('amount'); // in INR (whole rupees)
            $table->string('currency', 3)->default('INR');
            $table->string('frequency')->default('one_time'); // one_time|monthly|yearly
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('wants_80g_receipt')->default(true);
            $table->text('dedication')->nullable(); // "in memory of...", etc.
            $table->text('message')->nullable();

            // Payment tracking
            $table->string('payment_method')->default('razorpay'); // razorpay|upi|bank_transfer|cash|cheque
            $table->string('payment_status')->default('pending'); // pending|processing|success|failed|refunded
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_signature')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('payment_meta')->nullable();

            // Receipt
            $table->string('receipt_no')->nullable();
            $table->timestamp('receipt_issued_at')->nullable();

            $table->timestamps();

            $table->index(['payment_status', 'created_at']);
            $table->index('donor_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
