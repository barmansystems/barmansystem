<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('customer_id');
            $table->enum('created_in',['website', 'automation','app']);
            $table->enum('req_for', ['pre-invoice','invoice'])->comment('درخواست برای (پیش فاکتور و فاکتور)');
            $table->string('economical_number')->nullable()->comment('شماره اقتصادی');
            $table->string('national_number')->comment('شماره ملی');
            $table->string('need_no')->nullable();
            $table->string('province');
            $table->string('city');
            $table->text('address');
            $table->string('postal_code');
            $table->string('phone');
            $table->enum('status',['invoiced','pending','order'])->default('order');
            $table->unsignedInteger('discount')->comment('تخفیف نهایی');
            $table->longText('description')->nullable();
            $table->text('order_status_desc')->nullable();
            $table->text('payment_doc')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
