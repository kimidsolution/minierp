<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_taxes', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->uuid('account_id');
            $table->uuid('invoice_id');
            $table->decimal('amount', 15, 2);
            $table->timestamps();
            $table->softDeletes('deleted_at');

            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_taxes');
    }
}
