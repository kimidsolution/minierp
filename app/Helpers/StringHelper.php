<?php

namespace App\Helpers;

use Carbon\Carbon;
use DateTime;
use Str;

class StringHelper
{
    /**
     * get single message error, from result validate form request laravel
     *
     * @param  array $name     name company
     * @return string     if list error message is valid, return single message error
     */
    public function setNamelogoCompany($name)
    {
        if (strpos($name, ".") !== false) {
            $withoutDot = str_replace('.', '', $name);
            $namaCompany = str_replace(' ', '-', $withoutDot);
        } else {
            $namaCompany = str_replace(' ', '-', $name);
        }

        return $namaCompany . '_' . Str::random(5) . date('Ymdhis');
    }

    public function setNameSignUserByEmail($email)
    {
        $explodeString = explode('@', $email);
        $name = str_replace('.', '', $explodeString[0]);
        return 'sign_' . $name . '_' . Str::random(5) . date('Ymdhis');
    }

    public function parseDateFormat($date, $with_format = 'd-m-Y')
    {
        $parseDate = date_create_from_format($with_format, $date);
        return date_format($parseDate, 'Y-m-d');
    }

    public function parseStartOrLastDateOfMonth($date, $format, $is_end)
    {
        if ($is_end) {
            return Carbon::create($date)->lastOfMonth()->format($format);
        }
        return Carbon::create($date)->startOfMonth()->format($format);
    }

    public function getDiscountPersent($total, $diskon)
    {
        if ($diskon <> 0) {
            $persentase = ($diskon / $total) * 100;

            return $persentase;
        }

        return 0;
    }

    public function defFloat($value)
    {
        $result = 0;
        if ($value) $result = $value != '0.00' ? $value : 0;
        return (float) $result;
    }

    public function defFormatCurrency($value, $currency = null)
    {
        if ($currency) {
            return $currency .  number_format($value, 0, ',', '.');
        }
        return number_format($value, 0, ',', '.');
    }

    public function checkIsNegative($value)
    {
        if ($value) {
            return $value < 0;
        }
        return false;
    }

    public function getStringCheckingBalance($val_one, $val_two)
    {
        if ($val_two && $val_two) {
            return (abs($val_one - $val_two) < 0.00001) ? 'Balance' : 'Not Balance';
        }
        return '';
    }

    public function countRangeDate($from_date, $to_date, $format = 'days')
    {
        $start_date = new DateTime($from_date);
        $end_date = new DateTime($to_date);
        $diff = $start_date->diff($end_date);

        switch ($format) {
            case 'years':
                return $diff->y;
                break;
            case 'months':
                return $diff->m;
                break;
            default:
                return $diff->d;
                break;
        }
    }
}
