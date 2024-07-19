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
        Schema::create('booking_details', function (Blueprint $table) {
            $table->increments('booking_id');
            $table->unsignedInteger('customer_id');
            $table->date('booking_date');
            $table->integer('booking_type')->comment('1 = Full Day | 2 = Half Day | 3 = Custom');
            $table->integer('booking_slot')->nullable()->comment('1 = First Half | 2 = Second Half')->default(NULL);
            $table->time('from_time')->nullable()->default(NULL);
            $table->time('to_time')->nullable()->default(NULL);
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('customers')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_details');
    }
};
