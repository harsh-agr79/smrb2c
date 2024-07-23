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
        Schema::create('salesreturns', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('date');
            $table->string('returnid');
            $table->string('name');
            $table->string('user_id');
            $table->string('item');
            $table->string('product_id');
            $table->string('category');
            $table->string('category_id');
            $table->string('brand');
            $table->string('brand_id');
            $table->string('net')->nullable();
            $table->string('price');
            $table->string('quantity');
            $table->string('discount')->default('0');
            $table->string('sdis')->default('0');
            $table->string('nepday');
            $table->string('nepmonth');
            $table->string('nepyear');
            $table->longText('remarks')->nullable();
            $table->string('extra1')->nullable();
            $table->string('extra2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salesreturns');
    }
};
