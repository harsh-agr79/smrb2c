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
        Schema::create('front', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('image')->nullable();
            $table->string('type')->nullable();
            $table->string('message', 1000)->nullable();
            $table->integer('ordernum')->nullable();
            $table->string('extra1')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('front');
    }
};
