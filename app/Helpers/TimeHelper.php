<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimeHelper
{
    public static function now()
    {
        return Carbon::now('Africa/Addis_Ababa');
    }
    
    public static function today()
    {
        return Carbon::today('Africa/Addis_Ababa');
    }
    
    public static function formatDate($date, $format = 'd/m/Y')
    {
        if (!$date) return '';
        return Carbon::parse($date)->setTimezone('Africa/Addis_Ababa')->format($format);
    }
    
    public static function formatDateTime($datetime, $format = 'd/m/Y H:i:s')
    {
        if (!$datetime) return '';
        return Carbon::parse($datetime)->setTimezone('Africa/Addis_Ababa')->format($format);
    }
}
