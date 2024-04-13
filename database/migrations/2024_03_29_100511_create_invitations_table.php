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
        Schema::create('invitations', function (Blueprint $table) {
            $table->unsignedBigInteger('invitation_id');
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('auction_id');
            $table->unsignedBigInteger('buyer_id');
            $table->timestamps();

            $table->primary(['invitation_id', 'auction_id', 'buyer_id']);
            $table->foreign('auction_id')->references('auction_id')->on('auctions')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
