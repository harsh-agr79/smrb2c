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
        Schema::create('products', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name');
            $table->bigInteger('brand_id');
            $table->string('brand');
            $table->bigInteger('category_id');
            $table->string('category');
            $table->string('stock')->nullable();
            $table->string('hide')->nullable();
            $table->string('price');
            $table->string('featured')->nullable();
            $table->string('net')->nullable();
            $table->longText('details')->nullable();
            $table->longText('images')->nullable();
            $table->bigInteger('ordernum')->nullable();
            $table->string('offer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
