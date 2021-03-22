<?php

namespace App\Services\Api\Finance\Voucher\Create;

use Illuminate\Http\Request;

class ReformattedData
{
    public static function handle(Request $request)
    {
        $data = $request->data;

        foreach ($data as $key => $value) {
            $additionalAccountTemps = [];
            if (array_key_exists('additional_accounts', $value)) {
                $additionalAccounts = $value['additional_accounts'];
                foreach ($additionalAccounts as $keyAc => $valueAc) {
                    if (!is_null($valueAc)) {
                        $additionalAccountTemps[$keyAc] = $valueAc;
                    }
                }
                $data[$key]['additional_accounts'] = $additionalAccountTemps;
            } else {
                $data[$key]['additional_accounts'] = [];
            }
        }

        $request->request->add([
            'data_reformatted' => $data
        ]);
    }
}