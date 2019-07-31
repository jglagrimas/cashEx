<?php
namespace App\Repositories;

use App\Helpers\CurrencyConversion;
use App\Helpers\DateTime;
use Evp\Component\Money\Money;
use ConstVar;


class CashTranscationRepository
{

    protected $transactionRecords;


    public function __construct($transactionRecords)
    {
       $this->transactionRecords = $transactionRecords; //Set all Transaction to check for weekly.
    }

    /*
      /$$$$$$   /$$$$$$   /$$$$$$  /$$   /$$       /$$$$$$ /$$   /$$
     /$$__  $$ /$$__  $$ /$$__  $$| $$  | $$      |_  $$_/| $$$ | $$
    | $$  \__/| $$  \ $$| $$  \__/| $$  | $$        | $$  | $$$$| $$
    | $$      | $$$$$$$$|  $$$$$$ | $$$$$$$$        | $$  | $$ $$ $$
    | $$      | $$__  $$ \____  $$| $$__  $$        | $$  | $$  $$$$
    | $$    $$| $$  | $$ /$$  \ $$| $$  | $$        | $$  | $$\  $$$
    |  $$$$$$/| $$  | $$|  $$$$$$/| $$  | $$       /$$$$$$| $$ \  $$
     \______/ |__/  |__/ \______/ |__/  |__/      |______/|__/  \__/

    */


    /**
     * get commission fee cash in
     *
     * @param array $transactionRecord
     *
     * @return money
     */
    public function processCashIn($transactionRecord)
    {
        return $this->getCommissionFeeCashIn($transactionRecord);
    }

    /**
     * compute for the commission fee cash in
     *
     * @param array $transactionRecord
     *
     * @return money
     */
    public function getCommissionFeeCashIn($transactionRecord)
    {
        //Convert to Base Currency EUR
        $amountInEUR = CurrencyConversion::convertToEUR(
                $transactionRecord['operation_amount'],
                $transactionRecord['operation_currency']
            );

        //Compute for Commission
        $commissionInEUR = new money($amountInEUR->mul(ConstVar::COMM_FEE_CASH_IN)->getAmount(),ConstVar::BASE_CURRENCY);
      
        // check if the Commission is greater than the max Commission as set
        if($commissionInEUR->isGt(new money(ConstVar::MAX_COMM_FEE_CASH_IN_EUR,ConstVar::BASE_CURRENCY))){
            $commissionInEUR->setAmount(constVar::MAX_COMM_FEE_CASH_IN_EUR);
        }

        $commissionAmount =  CurrencyConversion::convertEURToOtherCurrency(
                $commissionInEUR->getAmount(),
                $transactionRecord['operation_currency']
            );

         return $commissionAmount;
    }


    /*    
       /$$$$$$   /$$$$$$   /$$$$$$  /$$   /$$        /$$$$$$  /$$   /$$ /$$$$$$$$
      /$$__  $$ /$$__  $$ /$$__  $$| $$  | $$       /$$__  $$| $$  | $$|__  $$__/
     | $$  \__/| $$  \ $$| $$  \__/| $$  | $$      | $$  \ $$| $$  | $$   | $$   
     | $$      | $$$$$$$$|  $$$$$$ | $$$$$$$$      | $$  | $$| $$  | $$   | $$   
     | $$      | $$__  $$ \____  $$| $$__  $$      | $$  | $$| $$  | $$   | $$   
     | $$    $$| $$  | $$ /$$  \ $$| $$  | $$      | $$  | $$| $$  | $$   | $$   
     |  $$$$$$/| $$  | $$|  $$$$$$/| $$  | $$      |  $$$$$$/|  $$$$$$/   | $$   
      \______/ |__/  |__/ \______/ |__/  |__/       \______/  \______/    |__/   
    */

    /**
     * get all user transction
     *
     * @param int $userId
     *
     * @return array
     */
    public function getUserAllCashOutTransaction($userId)
    {   
        return collect($this->transactionRecords)
            ->where('user_id',$userId)
            ->where('operation_type','cash_out');
    }


    /**
     * get commission fee cash out
     *
     * @param array $transactionRecord
     *
     * @return money
     */
    public function processCashOut($transactionRecord)
    {
        return $this->processCommissionFeeCashOut($transactionRecord);
    }



    /**
     * check if what type of user
     *
     * @param array $transactionRecord
     *
     * @return money
     */
    public function processCommissionFeeCashOut($transactionRecord)
    {
                                                                                                                                        
        switch ($transactionRecord['user_type']) {
            case  ConstVar::USER_TYPE_NATURAL:
                return $this->getCommissionFeeCashOutNatural($transactionRecord);
                break;
            case  ConstVar::USER_TYPE_LEGAL:
                return $this->getCommissionFeeCashOutLegal($transactionRecord);
                break;
           default:
               # code...
               break;
        }
    }


    /**
     * compute for the commission fee cash out Legal
     *
     * @param array $transactionRecord
     *
     * @return money
     */
    public function getCommissionFeeCashOutLegal($transactionRecord)
    {
        //Convert to Base Currency EUR
        $amountInEUR = CurrencyConversion::convertToEUR(
                $transactionRecord['operation_amount'],
                $transactionRecord['operation_currency']
            );

         //Compute for Commission
        $commissionInEUR = new money($amountInEUR->mul(ConstVar::COMM_FEE_CASH_OUT)->getAmount(),ConstVar::BASE_CURRENCY);
      

        if($commissionInEUR->isLt(new money(ConstVar::MIN_COMM_FEE_CASH_OUT_EUR,ConstVar::BASE_CURRENCY))){
            $commissionInEUR->setAmount(0);
        }

        $commissionAmount = CurrencyConversion::convertEURToOtherCurrency(
                $commissionInEUR->getAmount(),
                $transactionRecord['operation_currency']
            );

        return $commissionAmount;
    }


    /**
     * compute for the commission fee cash out Natural
     *
     * @param array $transactionRecord
     *
     * @return money
     */
    public function getCommissionFeeCashOutNatural($transactionRecord)
    {

        $amountInEUR = CurrencyConversion::convertToEUR(
                $transactionRecord['operation_amount'],
                $transactionRecord['operation_currency']
            );

        //Default commission
        $commissionInEUR = new money(
                $amountInEUR->mul(ConstVar::COMM_FEE_CASH_OUT)->getAmount(),
                ConstVar::BASE_CURRENCY
            );

        //Get the Same week transaction for the current processed Transaction
        // dont repeat processing transaction
        $userWeekTransaction = [];
        $totalWeekTransactionInEUR = new money(0,ConstVar::BASE_CURRENCY);

        $allUserTransactions = $this->getUserAllCashOutTransaction($transactionRecord['user_id']);
        foreach ($allUserTransactions as $userTransaction) {
           
            if(DateTime::checkIfDateSameWeek($transactionRecord['operation_date'], $userTransaction['operation_date']) 
                && $transactionRecord['id'] > $userTransaction['id'])
            {
                array_push($userWeekTransaction, $userTransaction);
                $weekTransactionToEUR = CurrencyConversion::convertToEUR(
                        $userTransaction['operation_amount'],
                        $userTransaction['operation_currency']
                    );

                $totalWeekTransactionInEUR->setAmount($totalWeekTransactionInEUR->add($weekTransactionToEUR)->getAmount());
            }
          
        } 

        $maxCashOutPerWeek = new money(ConstVar::CASH_OUT_PER_WEEK_DISC_IN_EUR,ConstVar::BASE_CURRENCY);

        //Check if amount cash out exceed per week and cash transaction count not exceed
        if($totalWeekTransactionInEUR->isLt($maxCashOutPerWeek) 
            && count($userWeekTransaction) < ConstVar::MAX_TRANSACTION_PER_WEEK)
        {

            $totalWeekTransactionAndAmount = new money(
                    $amountInEUR->getAmount() + $totalWeekTransactionInEUR->getAmount(),
                    ConstVar::BASE_CURRENCY
                );

            if($amountInEUR->isGt($maxCashOutPerWeek)  || $totalWeekTransactionAndAmount->isGt($maxCashOutPerWeek)){

                $allTotalWeekTransction = new money(
                        $amountInEUR->getAmount() + $totalWeekTransactionInEUR->getAmount() - $maxCashOutPerWeek->getAmount(),
                        ConstVar::BASE_CURRENCY 
                    ) ;

                $commissionAmount = CurrencyConversion::convertEURToOtherCurrency(
                        $allTotalWeekTransction->mul(ConstVar::COMM_FEE_CASH_OUT)->getAmount(),
                        $transactionRecord['operation_currency']
                    );

                return $commissionAmount;
            }
            else{

                $commissionAmount = CurrencyConversion::convertEURToOtherCurrency(
                        0,
                        $transactionRecord['operation_currency']
                    );

                return $commissionAmount;
            }
        }

        $commissionAmount =  CurrencyConversion::convertEURToOtherCurrency(
                $commissionInEUR->getAmount(),
                $transactionRecord['operation_currency']
            );

        return  $commissionAmount;
    }

}
