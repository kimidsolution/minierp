<?php

namespace App\Services\Api\Finance\Report\ProfitLoss;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Services\Api\Finance\Report\ProfitLoss\ValidateRequest;

class ProfitLossService
{
    public function handle(Request $request)
    {
        try {
            ValidateRequest::handle($request);
            GetDataAccountType::handle($request);
            GetDataProfitLoss::handle($request);
            $response = [
                'resource_income' => $request->resource_income,
                'resource_cogs' => $request->resource_cogs,
                'resource_expense' => $request->resource_expense,
                'resource_other_income' => $request->resource_other_income,
                'resource_other_expense' => $request->resource_other_expense,
                'gross_profit' => $request->gross_profit,
                'net_income' => $request->net_income,
                'net_other' => $request->net_other,
                'total_profit_loss_beforetax' => $request->total_profit_loss_beforetax,
                'tax_peryear' => $request->tax_peryear,
                'total_profit_loss' => $request->total_profit_loss,
            ];

            if ($request->query('to_json') == "false") {
                $output = view('finance.report.profit-loss.table-fill', compact('request'))->render();
                return response()->json([
                    'status' => true,
                    'dataHtml' => $output,
                    'data' => $response
                ]);
            }
            return response()->jsonp($request->callback, $response);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'messages' => $e->getMessage()
            ]);
        }
    }
}
