<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use League\Csv\Statement;
use \PhpOffice\PhpSpreadsheet\IOFactory;

class CsvReader
{

    protected $headers;
    protected $storage = '';
    protected $storageDisk;

    /**
     * Set Storage Disk
     *
     * @param string|int $storageDisk
     */
    public function __construct($storageDisk)
    {
       $this->storage = $storageDisk;
    }


    /**
     * Set Headers in read CSV
     *
     * @param array $headers
     */
    public function setHeader($headers=[])
    {
        $this->headers = $headers;
    }

    /**
     * read CSV file
     *
     * @param string $filePath
     * @param boolean $withHeaders
     *
     * @return Array
     */
    public function read($filePath,$withHeaders = false)
    {     
        $reader = IOFactory::load($filePath);
        $return = [];
        foreach ($reader->getActiveSheet()->toArray() as $key => $record) {
            if($withHeaders){
                $csvResultContainer = [];
                foreach ($record as $recordKey => $val) {
                    $csvResultContainer['id']  = $key+1; //Adding Transaction Id to fetch unique
                    $csvResultContainer[ isset($this->headers[$recordKey]) ? $this->headers[$recordKey] : 'column'.$recordKey ]    = $val;
                };
                array_push($return,$csvResultContainer); 
            }else{
                array_unshift($record,$key);  //Adding Transaction Id to fetch unique
                array_push($return,$record);  
            }
        };
        return $return;
 
    }

}
