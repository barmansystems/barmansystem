<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_infos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('economic_number');
            $table->string('national_number');
            $table->string('national_id');
            $table->string('zip_code');
            $table->string('phone_number');
            $table->text('address');
            $table->string('fax_number')->default(0);
            $table->string('mobile_number')->default(0);
            $table->string('shaba_number')->default(0);
            $table->string('bank_account_number')->default(0);
            $table->string('account_user_name')->nullable();
            $table->string('account_user_password')->nullable();
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
        Schema::dropIfExists('company_infos');
    }
}
