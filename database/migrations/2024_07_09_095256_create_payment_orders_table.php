<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePaymentOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('number')->unique();
            $table->enum('type', ['payments', 'receive']);
            $table->enum('status', ['approved', 'failed','pending'])->default('pending');
            $table->longText('description')->nullable();
            $table->string('amount');
            $table->string('amount_words');
            $table->string('invoice_number')->default(0);
            $table->string('for');
            $table->string('to')->nullable();
            $table->string('from')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_number')->nullable();
            $table->boolean('is_online_payment')->default(false);
            $table->string('site_name')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('payment_orders');
    }
}
