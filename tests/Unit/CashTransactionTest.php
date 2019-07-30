<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Repositories\CashTranscationRepository;
use ConstVar;

class CashTransactionTest extends TestCase
{	


    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_cash_in_commission()
    {
    	$expectedCommission = 0.08;
    	$cashTransactionRepo = new CashTranscationRepository([]);
    	$cashTransaction = [
    		'id' => 1,
            'operation_date' => '2016-02-19',
            'user_id'=> 1,
            'user_type' => 'natural',
            'operation_type' => 'cash_in',
            'operation_amount'=> 250,
            'operation_currency' => 'USD' ,
        ];

        $commission = $cashTransactionRepo->processCashIn($cashTransaction);
        $this->assertEquals($commission->ceil()->getAmount(), $expectedCommission);
    }

      /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_cash_out_legal_commission()
    {
    	$expectedCommission = 181;
    	$cashTransactionRepo = new CashTranscationRepository([]);
    	$cashTransaction = [
    		'id' => 2,
            'operation_date' => '2016-02-19',
            'user_id'=> 2,
            'user_type' => 'legal',
            'operation_type' => 'cash_out',
            'operation_amount'=> 60200,
            'operation_currency' => 'JPY' ,
        ];

        $commission = $cashTransactionRepo->processCashOut($cashTransaction);
        $this->assertEquals($commission->ceil()->getAmount(), $expectedCommission);
    }


     /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_cash_out_natural_commission_lower_than_limit_1000()
    {
    	$expectedCommission = 0.00;
    	$cashTransactionRepo = new CashTranscationRepository([]);
    	$cashTransaction = [
    		'id' => 3,
            'operation_date' => '2016-02-20',
            'user_id'=> 3,
            'user_type' => 'natural',
            'operation_type' => 'cash_out',
            'operation_amount'=> 850,
            'operation_currency' => 'EUR' ,
        ];

        $commission = $cashTransactionRepo->processCashOut($cashTransaction);
        $this->assertEquals($commission->ceil()->getAmount(), $expectedCommission);
    }


      /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_cash_out_natural_commission_higher_than_limit_1000()
    {
    	$expectedCommission = 5.40;
    	$cashTransactionRepo = new CashTranscationRepository([]);
    	$cashTransaction = [
    		'id' => 3,
            'operation_date' => '2016-02-20',
            'user_id'=> 3,
            'user_type' => 'natural',
            'operation_type' => 'cash_out',
            'operation_amount'=> 2800,
            'operation_currency' => 'EUR' ,
        ];

        $commission = $cashTransactionRepo->processCashOut($cashTransaction);
        $this->assertEquals($commission->ceil()->getAmount(), $expectedCommission);
    }

          /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_cash_out_natural_commission_have_3_transaction_on_week()
    {
    	$expectedCommission = 1.50;

    	$weeklyCashTransaction[] = [
    		'id' => 1,
            'operation_date' => '2016-02-22',
            'user_id'=> 3,
            'user_type' => 'natural',
            'operation_type' => 'cash_out',
            'operation_amount'=> 900,
            'operation_currency' => 'USD' ,
        ];
        $weeklyCashTransaction[] = [
    		'id' => 2,
            'operation_date' => '2016-02-23',
            'user_id'=> 3,
            'user_type' => 'natural',
            'operation_type' => 'cash_out',
            'operation_amount'=> 60,
            'operation_currency' => 'EUR' ,
        ];
        $weeklyCashTransaction[] = [
    		'id' => 3,
            'operation_date' => '2016-02-24',
            'user_id'=> 3,
            'user_type' => 'natural',
            'operation_type' => 'cash_out',
            'operation_amount'=> 1000,
            'operation_currency' => 'EUR' ,
        ];
    	$cashTransactionRepo = new CashTranscationRepository($weeklyCashTransaction);
    	 
    	$cashTransaction = [
    		'id' => 4,
            'operation_date' => '2016-02-25',
            'user_id'=> 3,
            'user_type' => 'natural',
            'operation_type' => 'cash_out',
            'operation_amount'=> 500,
            'operation_currency' => 'EUR' ,
        ];

        $commission = $cashTransactionRepo->processCashOut($cashTransaction);
        $this->assertEquals($commission->ceil()->getAmount(), $expectedCommission);
    }
}
