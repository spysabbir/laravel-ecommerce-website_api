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
        Schema::create('order_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->unsignedBigInteger('user_id');
            $table->string('shipping_name');
            $table->string('shipping_phone');
            $table->string('shipping_email');
            $table->integer('shipping_district_id');
            $table->integer('shipping_upazila_id');
            $table->integer('shipping_union_id');
            $table->text('shipping_address');
            $table->string('shipping_method');
            $table->string('status')->default('pending');
            $table->string('payment_method');
            $table->string('payment_status');
            $table->string('coupon_code')->nullable();
            $table->string('sub_total');
            $table->string('discount')->nullable();
            $table->string('shipping_cost');
            $table->decimal('total', 8, 2);
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_summaries');
    }
};
