<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceConfigurationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_configuration_details', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->uuid('account_id');
            $table->uuid('finance_configuration_id');

            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('finance_configuration_id')->references('id')->on('finance_configurations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('finance_configuration_details');
    }
}
