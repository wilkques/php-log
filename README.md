# Log for PHP

[![Latest Stable Version](https://poser.pugx.org/wilkques/php-log/v/stable)](https://packagist.org/packages/wilkques/php-log)
[![License](https://poser.pugx.org/wilkques/php-log/license)](https://packagist.org/packages/wilkques/php-log)

````
composer require wilkques/php-log
````

## How to use
```php
$log = new \Wilkques\Log\Log;

$log->logName('<change log name>'); // default system.log

$log->path('<change log path>'); // default ./storage/logs

$log->info(123);

$log->debug(123);

$log->warning(123);

$log->error(123);

$log->critical(123);

$log->error(
    $log->exceptionLog(new \Exception(123, 400))
);

$log->critical(
    $log->exceptionLog(new \Exception(456, 500))
);

```

output

```log
[2023-06-13 17:02:20] [INFO] : 123 
[Information] 
#0 arguments: 
[]

[2023-06-13 17:02:20] [DEBUG] : 123 
[Information] 
#0 arguments: 
[]

[2023-06-13 17:02:20] [WARNING] : 123 
[Information] 
#0 arguments: 
[]

[2023-06-13 17:02:20] [ERROR] : 123 

[2023-06-13 17:02:20] [CRITICAL] : 123 

[2023-06-13 17:02:20] [ERROR] : 123 
ERROR(code:400) 
/var/www/try/app/Console/ChatGpt.php:(69) 
[stackTrace] 
#0 /var/www/try/app/Console/ChatGpt.php(37): App\Console\ChatGpt->httpRequest()
#1 /var/www/try/vendor/wilkques/console/src/Console.php(168): App\Console\ChatGpt->handle()
#2 /var/www/try/artisan(18): Wilkques\Console\Console->handle()
#3 {main}
 

[2023-06-13 17:02:20] [CRITICAL] : 456 
ERROR(code:500) 
/var/www/try/app/Console/ChatGpt.php:(70) 
[stackTrace] 
#0 /var/www/try/app/Console/ChatGpt.php(37): App\Console\ChatGpt->httpRequest()
#1 /var/www/try/vendor/wilkques/console/src/Console.php(168): App\Console\ChatGpt->handle()
#2 /var/www/try/artisan(18): Wilkques\Console\Console->handle()
#3 {main}
```