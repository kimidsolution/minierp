<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->date('transaction_date');
            $table->uuid('model_id')->nullable();
            $table->string('model_type')->nullable();
            $table->boolean('transaction_type')->comment('false = Receivable, true = Payable');
            $table->tinyInteger('transaction_status')->comment('1 = draft, 2 = posted');
            $table->string('reference_number')->nullable();
            $table->text('description');
            $table->uuid('company_id');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at');

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
        Schema::dropIfExists('transactions');
    }
}
