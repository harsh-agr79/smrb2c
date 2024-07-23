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
        Schema::create('customers', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name');
            $table->string('shopname');
            $table->string('userid');
            $table->string('email');
            $table->string('uniqueid');
            $table->longText('password');
            $table->string('dob')->nullable();
            $table->string('contact');
            $table->string('contact2')->nullable();
            $table->string('address');
            $table->string('area')->nullable();
            $table->string('state')->nullable();
            $table->string('district')->nullable();
            $table->string('marketer')->nullable();
            $table->string('marketer_id')->nullable();
            $table->string('tax_type')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('tax_certificate')->nullable();
            $table->string('type');
            $table->string('profileimg')->nullable();
            $table->longText('brands')->nullable();
            $table->longText('cart')->nullable();
            $table->longText('balance')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
