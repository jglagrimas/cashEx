<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\csvReader;
use App\Repositories\CashTranscationRepository;
use ConstVar;

class ProcessTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'processTransaction {filePath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $csvReader = new csvReader('local');

        // Should be parallel to the columns
        // if lacking of column it will name it 'column[key]'
        $csvReader->setHeader([
                'operation_date',
                'user_id',
                'user_type',
                'operation_type',
                'operation_amount',
                'operation_currency',
            ]) ;

        $filePath = $this->argument('filePath');
        
        $withHeaders = true;
        $csvResultArray = $csvReader->read($filePath,$withHeaders);
        $cashTranscationRepository = new CashTranscationRepository($csvResultArray);
        print_r($csvResultArray);
        foreach ( $csvResultArray as  $transactionRecord) {
            switch ($transactionRecord['operation_type']) {
                case ConstVar::OPERATION_TYPE_CASH_OUT:
                    $commissionMoneyAmount = $cashTranscationRepository->processCashOut($transactionRecord);
                    break;
                case ConstVar::OPERATION_TYPE_CASH_IN:
                    $commissionMoneyAmount = $cashTranscationRepository->processCashIn($transactionRecord);
                    break;
                default:
                    fwrite(STDOUT, 'Operation Type Unknown'.PHP_EOL);
                    break;
            }
            fwrite(STDOUT, $commissionMoneyAmount->ceil()->getAmount().PHP_EOL);
        }
     
        
    }
}
