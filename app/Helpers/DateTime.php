<?php
namespace App\Helpers;
use Carbon\Carbon;

class DateTime
{
	 /**
     * Compare two dates if same week
     *
     * @param date $startDate
     * @param date $endDate
     *
     * @return Boolean
     */
    static function checkIfDateSameWeek($startDate,$endDate)
    {                  
        $startDateCarbon = new Carbon($startDate);
        $endDateCarbon = new Carbon($endDate);

		$diff = $startDateCarbon->diffInDays($endDateCarbon);
        return $startDateCarbon->weekOfYear == $endDateCarbon->weekOfYear 
         	&& $diff <= 7 ;
    }

}
