<?php

namespace App\Services\Api\Finance\Report\Journal;

use App\Services\Api\Finance\Report\Journal\ValidateRequest;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class JournalService
{
    public function handle(Request $request)
    {
        try {
            ValidateRequest::handle($request);
            GetDataJournal::handle($request);

            $response = $request->data_general_ledger;
            $links = $request->links;

            if ($request->query('to_json') == "false") {
                $output = view('finance.report.journal.table-fill', compact('response', 'links'))->render();
                return response()->json([
                    'status' => 200,
                    'dataHtml' => $output,
                    'links' => $links
                ]);
            }
            return response()->jsonp($request->callback, [
                'data' => $response,
                'links' => $links
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'messages' => $e->getMessage()
            ]);
        }
    }
}
