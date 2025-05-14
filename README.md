# Log for PHP

[![Latest Stable Version](https://poser.pugx.org/wilkques/log/v/stable)](https://packagist.org/packages/wilkques/log)
[![License](https://poser.pugx.org/wilkques/log/license)](https://packagist.org/packages/wilkques/log)

````
composer require wilkques/log
````

## How to use
```php
$log = \Wilkques\Log\Log::make();

// or

$log = logger();

$log->logName('<change log name>'); // default system.log

$log->setDirectory('<change log path>'); // default ./storage/logs

$log->info(123);

$log->debug(123);

$log->warning(123);

$log->error(123);

$log->critical(123);

$log->error(new \Exception(123));

$log->critical(new \Exception(456));

// or

Wilkques\Log\Log::info('123');
Wilkques\Log\Log::debug('123');
Wilkques\Log\Log::warning('123');
Wilkques\Log\Log::error('123');
Wilkques\Log\Log::critical('123');
Wilkques\Log\Log::error(new \Exception(123));
Wilkques\Log\Log::critical(new \Exception(456));
```

output

```log
[2025-05-14 11:29:14] [INFO] 123
[2025-05-14 11:29:14] [DEBUG] 123
[2025-05-14 11:29:14] [WARNING] 123
[2025-05-14 11:29:14] [ERROR] 123
[2025-05-14 11:29:14] [CRITICAL] 123
[2025-05-14 11:29:14] [ERROR] exception 'Exception' with message '123' in C:\works\projects\packages\54\test.php:70
Stack trace:
#0 {main}
[2025-05-14 11:29:14] [CRITICAL] exception 'Exception' with message '456' in C:\works\projects\packages\54\test.php:71
Stack trace:
#0 {main}
```