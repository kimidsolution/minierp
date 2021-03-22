<?php

namespace App\Jobs;

use DB;
use Storage;
use Illuminate\Bus\Queueable;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ImportFileTransaction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Imports\TransactionExcelCoaSheetImport;
use App\Imports\TransactionExcelJournalSheetImport;

class ImportDataTransactionFromExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fileName;
    protected $companyId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fileName, $companyId)
    {
        $this->fileName = $fileName;
        $this->companyId = $companyId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $importFile = ImportFileTransaction::where('file_name', $this->fileName)
                        ->where('company_id', $this->companyId)
                        ->first();

        if (is_null($importFile))
            return 'dokumen tidak ditemukan';

        if (ImportFileTransaction::STATUS_HAS_BEEN_UPLOADED != $importFile->import_status)
            return 'dokumen sedang / sudah diproses';

        $importFile->import_status = ImportFileTransaction::STATUS_ON_PROGRESS_FETCH_DATA;
        $importFile->save();

        DB::beginTransaction();

        try {

            Excel::import(
                new TransactionExcelCoaSheetImport($this->companyId),
                Storage::disk('transaction_import')->path($importFile->file_name)
            );

            Excel::import(
                new TransactionExcelJournalSheetImport($this->companyId),
                Storage::disk('transaction_import')->path($importFile->file_name)
            );

            $importFile->import_status = ImportFileTransaction::STATUS_DATA_HAS_BEEN_RECORDED;
            $importFile->save();

            DB::commit();
            echo 'finish import data from ' . $this->fileName;

        } catch (\Exception $e) {

            DB::rollback();
            $importFile->error = $e->getMessage();
            $importFile->import_status = ImportFileTransaction::STATUS_FAILED_IMPORT_DATA;
            $importFile->save();
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
