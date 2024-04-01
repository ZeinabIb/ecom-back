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
        Schema::create('auctions', function (Blueprint $table) {
            $table->unsignedBigInteger('auction_id');
            $table->decimal('starting_price', 8, 2);
            $table->decimal('current_highest_bid', 8, 2)->nullable();
            $table->timestamp('auction_start_time')->nullable();
            $table->timestamp('auction_end_time')->nullable();
//            $table->timestamp('auction_end_time');
//            $table->timestamp('auction_start_time');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('store_id');
            $table->timestamps();

            $table->primary(['auction_id', 'product_id', 'store_id']);
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
