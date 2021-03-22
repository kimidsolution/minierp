<?php

namespace App\Console\Commands;

use DB;
use Storage;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TransactionExcelCoaSheetImport;
use App\Imports\TransactionExcelJournalSheetImport;

class ImportCoaTransactionBaco extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'baco:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $companyId = '2a8a406e-bbc2-4e83-a21e-601b428fdd98';
        $fileName = 'cv_tongga_samudra_2019.xlsx';

        DB::beginTransaction();

        try {

            Excel::import(
                new TransactionExcelCoaSheetImport($companyId),
                Storage::disk('transaction_import')->path($fileName)
            );

            Excel::import(
                new TransactionExcelJournalSheetImport($companyId),
                Storage::disk('transaction_import')->path($fileName)
            );

            DB::commit();
            echo 'import data coa & transaction is finish';

        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }
}
