<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->uuid('id')->unique();
            $table->uuid('company_id');
            $table->uuid('partner_id');
            $table->uuid('downpayment_account_id');
            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('payment_status');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->unsignedTinyInteger('is_posted');
            $table->unsignedTinyInteger('sent_to_partner');
            $table->string('invoice_number')->unique();
            $table->decimal('discount', 15, 2);
            $table->decimal('down_payment', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->text('note')->nullable();
            $table->string('purchase_order')->nullable();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at');

            $table->foreign('partner_id')->references('id')->on('partners');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
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
