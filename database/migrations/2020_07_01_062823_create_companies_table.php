<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('company_name')->unique();
            $table->string('brand_name')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->text('address');
            $table->string('logo')->nullable();
            $table->string('tax_id_number')->unique()->nullable();
            $table->string('fax')->unique()->nullable();
            $table->string('website')->unique()->nullable();
            $table->boolean('vat_enabled');
            $table->unsignedTinyInteger('status');
            $table->unsignedTinyInteger('type');
            $table->uuid('currency_id');
            $table->string('city');
            $table->string('country');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at');

            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
