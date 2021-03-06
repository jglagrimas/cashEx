<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Helpers\DateTime;

class DateTimeHelperTest extends TestCase
{
    /**
     * success if the date is on the same week
     *
     * @return void
     */
    public function test_success_if_same_week_()
    {
    	$startDate = '2016-02-23';
    	$endDate = '2016-02-24';
        $this->assertTrue(DateTime::checkIfDateSameWeek($startDate, $endDate) );
    }

     /**
     * fail checking if the date is on the same week
     *
     * @return void
     */
    public function test_fail_if_same_week_()
    {
    	$startDate = '2016-02-23';
    	$endDate = '2016-02-29';
        $this->assertFalse(DateTime::checkIfDateSameWeek($startDate, $endDate) );
    }
}
