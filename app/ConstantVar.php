<?php 
namespace App;

class ConstantVar {
    //Operation type
    const OPERATION_TYPE_CASH_OUT = 'cash_out';
    const OPERATION_TYPE_CASH_IN = 'cash_in';

    //User Type
    const USER_TYPE_LEGAL = 'legal';
    const USER_TYPE_NATURAL = 'natural';

    //Currency Convertion to EUR
    const JPY_TO_EUR = 129.53;
    const USD_TO_EUR = 1.1497;
    
    const BASE_CURRENCY = 'EUR';
    const CURRENCY_JPY = 'JPY';
    const CURRENCY_USD = 'USD';

    const CASH_OUT_PER_WEEK_DISC_IN_EUR = 1000;
    const MAX_TRANSACTION_PER_WEEK = 3;

    //Commission Fee
    const COMM_FEE_CASH_IN = 0.0003; // 0.03%
    const COMM_FEE_CASH_OUT = 0.003; // 0.3%

    const MAX_COMM_FEE_CASH_IN_EUR = 5.00;
    const MIN_COMM_FEE_CASH_OUT_EUR = 0.50;

}