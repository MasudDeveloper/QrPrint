<?php

namespace App\Helpers;

use Carbon\Carbon;

class BanglaHelper
{
    private static array $enDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    private static array $bnDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

    private static array $bnMonths = [
        1 => 'জানুয়ারি',
        2 => 'ফেব্রুয়ারি',
        3 => 'মার্চ',
        4 => 'এপ্রিল',
        5 => 'মে',
        6 => 'জুন',
        7 => 'জুলাই',
        8 => 'আগস্ট',
        9 => 'সেপ্টেম্বর',
        10 => 'অক্টোবর',
        11 => 'নভেম্বর',
        12 => 'ডিসেম্বর',
    ];

    private static array $bnMonthsShort = [
        1 => 'জানু',
        2 => 'ফেব্রু',
        3 => 'মার্চ',
        4 => 'এপ্রিল',
        5 => 'মে',
        6 => 'জুন',
        7 => 'জুলাই',
        8 => 'আগস্ট',
        9 => 'সেপ্টে',
        10 => 'অক্টো',
        11 => 'নভে',
        12 => 'ডিসে',
    ];

    /**
     * Convert English digits to Bengali digits
     */
    public static function bnNumber(int|float|string|null $number): string
    {
        if ($number === null || $number === '') {
            return '০';
        }

        return str_replace(self::$enDigits, self::$bnDigits, (string) $number);
    }

    /**
     * Format a DateTime object or string into human-friendly Bengali date
     * Example: "২৬ অক্টোবর, ২০২৬"
     */
    public static function bnDate(mixed $date, bool $shortMonth = false): string
    {
        if (!$date) {
            return '';
        }

        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        $day = self::bnNumber($date->day);
        $month = $shortMonth ? (self::$bnMonthsShort[$date->month] ?? '') : (self::$bnMonths[$date->month] ?? '');
        $year = self::bnNumber($date->year);

        return "{$day} {$month}, {$year}";
    }

    /**
     * Format amount in Taka with Bengali currency symbol and digits
     * Example: "৳ ৯৯৯"
     */
    public static function bnCurrency(int|float $amount, bool $showDecimals = false): string
    {
        $formatted = $showDecimals ? number_format($amount, 2) : number_format($amount);
        return '৳ ' . self::bnNumber($formatted);
    }
}

