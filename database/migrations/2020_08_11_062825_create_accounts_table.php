<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->uuid('company_id');
            $table->uuid('parent_account_id')->nullable();
            $table->string('account_name');
            $table->string('account_text')->nullable();
            $table->string('account_code');
            $table->tinyInteger('level')->default(1);
            $table->enum('balance', ['debit', 'credit']);
            $table->tinyInteger('account_type')
                ->comment = '1 = ASSETS(Harta), 2 = LIABILITIES(Kewajiban), 3 = CAPITALS(Modal), 4 = INCOME(Pendapatan), 5 = EXPENSES(Beban), 6 = COGS(Harga Pokok Penjualan), 7 = OTHER_INCOME(Pendapatan Lain), 7 = OTHER_EXPENSES(Beban Lain)';
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
        Schema::dropIfExists('accounts');
    }
}
