<?php
namespace App\Helpers;

use Evp\Component\Money\Money;
use ConstVar;

class CurrencyConversion
{

    /**
     * convert To EUR
     *
     * @param string|int $amount
     * @param string $currency
     *
     * @return Money
     */
    static function convertToEUR($amount,$currency=null)
    {   
        if(!$currency){
            fwrite(STDOUT, 'Operation Currency Unknown'.PHP_EOL);
        }

        $amountInMoney = new Money($amount , $currency);
        if(ConstVar::BASE_CURRENCY === $currency){
            return  $amountInMoney;
        }

        switch ($currency) {
            case ConstVar::CURRENCY_JPY:
                $returnAmount = $amountInMoney->div(ConstVar::JPY_TO_EUR);
                return new money($returnAmount->getAmount(),ConstVar::BASE_CURRENCY);
                break;
            case ConstVar::CURRENCY_USD:
                $returnAmount = $amountInMoney->div(ConstVar::USD_TO_EUR);
                return new money($returnAmount->getAmount(),ConstVar::BASE_CURRENCY);
                break;
        }
    }

    /**
     * contvert To Other Currency
     *
     * @param string|int $amount
     * @param string $currency
     *
     * @return Money
     */
    static function convertEURToOtherCurrency($amount,$currency=null)
    {
        if(!$currency){
            fwrite(STDOUT, 'Currency Unknown'.PHP_EOL);
        }

        $amountInMoney = new Money($amount , $currency);
        if($currency === ConstVar::BASE_CURRENCY){
            return $amountInMoney;
        }

        switch ($currency) {
            case ConstVar::CURRENCY_JPY:
                $returnAmount = $amountInMoney->mul(ConstVar::JPY_TO_EUR);
                return new money($returnAmount->getAmount(),ConstVar::CURRENCY_JPY);
                break;
            case ConstVar::CURRENCY_USD:
                $returnAmount = $amountInMoney->mul(ConstVar::USD_TO_EUR);
                return new money($returnAmount->getAmount(),ConstVar::CURRENCY_USD);
                break;
        }
    }

}
