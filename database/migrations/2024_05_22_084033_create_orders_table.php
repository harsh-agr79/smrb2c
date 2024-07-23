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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('date');
            $table->string('order_id');
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
            $table->string('approvedquantity');
            $table->string('mainstatus');
            $table->string('clnstatus')->nullable();
            $table->string('status');
            $table->string('discount')->default('0');
            $table->string('sdis')->default('0');
            $table->string('delivered')->nullable();
            $table->string('received')->nullable();
            $table->string('receiveddate')->nullable();
            $table->string('seen')->nullable();
            $table->string('seenby')->nullable();
            $table->string('deleted_at')->nullable();
            $table->string('save')->nullable();
            $table->string('marketer')->nullable();
            $table->string('nepday');
            $table->string('nepmonth');
            $table->string('nepyear');
            $table->longText('remarks')->nullable();
            $table->longText('userremarks')->nullable();
            $table->string('cartoons')->nullable();
            $table->string('transport')->nullable();
            $table->string('marketer_id')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
