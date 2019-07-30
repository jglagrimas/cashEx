### Installing
* Copy or clone the repo and run 
```shell
	composer install
```

### Running process
* type or copy (change the file path) 
```shell
	php.exe artisan processTransaction C:/Users/myPC/Desktop/cash_transaction.csv > output.log
```
* output directory will be in the root folder of cashEx
* or you can run
```shell
	php.exe artisan processTransaction C:/Users/myPC/Desktop/cash_transaction.csv
```
 * it will output result on the command line

### Running Unit Test
** go to root folder
** type
```shell
	./vendor/bin/phpunit --testdox
```

### Package used
* paysera lib-money : `https://github.com/paysera/lib-money`
* PHP spreedsheet : `https://phpspreadsheet.readthedocs.io/en/latest/topics/reading-and-writing-to-file/`

### Files used in the Process cash transaction
##### Main file (App/Console/Commands)
* `ProcessTransaction.php` : This will be called when run the command in the console.
##### Helpers (App/Helpers/)
* `CSVReader.php` : handles reading of CSV file
* `CurrencyConversion.php` : convert EUR to Other Currency(JPY,USD) and vice versa
* `DateTime.php` : check if the date is on the same week.
##### Repository (App/Repositories)
*  `CashTransactionRepository.php` : all the logic and processing methods are inside.
##### Constant File (App/)
* `ConstantVar.php` : all the Constant variable for computations are here.

##### Unit Test (test/Unit
* `CashTransactionTest.php` : Will check if the commission of cash in / cash out is correct.
* `CurrencyHelperTest.php` : Test Currency Helpers.
* `DateTimeHelperTest.php` : Test DateTime Helpers.
