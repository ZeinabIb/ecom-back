<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->unsignedBigInteger('seller_id');
        $table->string('location_lat'); // Assuming you'll store coordinates as a string
        $table->string('location_lng'); // Assuming you'll store coordinates as a string
        // Foreign key constraint
        $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropForeign(['seller_id']);
        $table->dropColumn(['seller_id', 'location']);
    });
}
};
