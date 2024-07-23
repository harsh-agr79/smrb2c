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
        Schema::create('payments', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('date');
            $table->string('name');
            $table->string('user_id');
            $table->string('type');
            $table->string('paymentid');
            $table->string('entry_by');
            $table->string('voucher')->nullable();
            $table->string('amount');
            $table->string('deleted')->nullable();
            $table->string('deleted_at')->nullable();
            $table->string('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
