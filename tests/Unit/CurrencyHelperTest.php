<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Helpers\CurrencyConversion;

class CurrencyHelperTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_conversion_JPY_to_EUR()
    {

    	$testMoney = [
	    		'amount' => 1 , 
	    		'currency' => 'JPY', 
	    		'expected_conversion' => 0.007720
	    	];

    	$result = CurrencyConversion::convertToEUR($testMoney['amount'],$testMoney['currency']);
        $this->assertEquals($result->getAmount(), $testMoney['expected_conversion']);
    }


     /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_conversion_USD_to_EUR()
    {

    	$testMoney = [
	    		'amount' => 1 , 
	    		'currency' => 'USD', 
	    		'expected_conversion' => 0.869792
	    	];

    	$result = CurrencyConversion::convertToEUR($testMoney['amount'],$testMoney['currency']);
        $this->assertEquals($result->getAmount(), $testMoney['expected_conversion']);
    }


    public function test_conversion_EUR_to_USD()
    {

    	$testMoney = [
	    		'amount' => 1 , 
	    		'currency_to_convert' => 'USD',
	    		'expected_conversion' => 1.1497
	    	];

    	$result = CurrencyConversion::convertEURToOtherCurrency($testMoney['amount'],$testMoney['currency_to_convert']);
        $this->assertEquals($result->getAmount(), $testMoney['expected_conversion']);
    }
    public function test_conversion_EUR_to_JPY()
    {

    	$testMoney = [
	    		'amount' => 1 , 
	    		'currency_to_convert' => 'JPY',
	    		'expected_conversion' => 129.53
	    	];

    	$result = CurrencyConversion::convertEURToOtherCurrency($testMoney['amount'],$testMoney['currency_to_convert']);
        $this->assertEquals($result->getAmount(), $testMoney['expected_conversion']);
    }
}
