<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('merchant_id');
            // TODO: Replace me with a brief explanation of why floats aren't the correct data type, and replace with the correct data type.
            // Because of possible accuracy and rounding difficulties, floats are not the appropriate data type to represent monetary values such as commission rates or discounts. 
            // Floats are binary representations of numbers that might cause mistakes when conducting computations with decimal values.
            // To correctly represent monetary amounts, it is advised to utilise decimal data types rather than floats. 
            // In Laravel, you may utilise the decimal method to define these columns in your migration file.
            $table->decimal('commission_rate', 2, 2);
            $table->string('discount_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affiliates');
    }
};
