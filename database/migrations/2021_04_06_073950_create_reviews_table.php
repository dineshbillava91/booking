<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();

            $table->unsignedInteger('rating');
            $table->text('content');
            $table->foreignId('bookable_id')->constrained('bookables');
            $table->foreignId('booking_id')->nullable()->constrained('bookings');

            //or 
            // $table->unsignedBigInteger('bookable_id')->index();
            // $table->foreign('bookable_id')->references('id')->on('bookables');

            // $table->unsignedBigInteger('booking_id')->index();
            // $table->foreign('booking_id')->references('id')->on('bookables');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
